<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Jobs\PaymentPendingJob;
use App\Models\Appointments;
use App\Models\PaymentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $keyId;
    private $secretKey;
    private $api;
    public $paymentResponseData = [];

    public function __construct()
    {
        $this->keyId = config('services.razorpay.RAZORPAY_KEY_ID');
        $this->secretKey = config('services.razorpay.RAZORPAY_SECRET_KEY');
        $this->api = new Api($this->keyId,$this->secretKey);
    }

    public function viewPaymentPage($id)
    {
        try {
            $appointments = Appointments::find($id);
            // $appointmentData = $appointments->load(['doctorTimeSlot','patients']);

            $paymentData = [
                'receipt'         => uniqid(),
                'amount'          => $appointments->amount * 100, // amount convert into paise (₹500)
                'currency'        => 'INR',
                'payment_capture' => 1
            ];

            $razorPayResponse = $this->api->order->create($paymentData);

            return $razorPayResponse->toArray();

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function processPayment(PaymentRequest $request)
    {
        try {
            $data = $request->validated();

            $api = new Api($this->keyId,$this->secretKey);

            $appointmentData = [
                'receipt'         => 'order_rcptid_11',
                'amount'          => $data['amount'], // amount convert into paise (₹500)
                'currency'        => 'INR',
                'payment_capture' => 1
            ];

            $razorPayOrder = $api->order->create($appointmentData);

        } catch (\Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = $th->getMessage();
        }
    }

    public function handlePayment(Request $request)
    {
        try {

            $this->api->utility->verifyPaymentSignature(array('razorpay_order_id' => $request->razorpay_order_id, 'razorpay_payment_id' => $request->razorpay_payment_id, 'razorpay_signature' => $request->razorpay_signature));

            // update the db
            Appointments::updatePaymentStatus($request->appointment_id);

            // fetch the payment details using the payment id
            $fetchPaymentDetails = $this->api->payment->fetch($request->razorpay_payment_id);

            $paymentResponseData = [
                'appointment_ID' => $request->appointment_id,
                'order_id' => $request->razorpay_order_id,
                'res_payment_id' => $fetchPaymentDetails->id,
                'transaction_id' => null,
                'method' => $fetchPaymentDetails->method,
                'email' => $fetchPaymentDetails->email,
                'phone' => $fetchPaymentDetails->contact,
                'payment_signature' => $request->razorpay_signature,
                'amount' => ($request->amount) ? $request->amount/100 : 0.00,
                'currency' => $request->currency,
                'status' => 'completed',
                'json_response' => json_encode((array)$fetchPaymentDetails),
                'createdBy' => auth()->user()->id
            ];

            PaymentDetails::create($paymentResponseData);

            return response()->json(['success' => true, 'message' => 'Payment successful']);

        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function fetchPaymentSummary(Request $request)
    {
        $appointment_id = $request->appointment_id;

        return $this->paymentSummary($appointment_id);
    }
    
    function showPaymentSuccess(Request $request)
    {
        $appointment_id = $request->appointment_id;

        $paymentData = $this->paymentSummary($appointment_id);

        return view('payments.success',['payment' => $paymentData]);    
    }

    function paymentSummary($appointment_id)
    {
        $paymentData = PaymentDetails::where('appointment_ID',$appointment_id)->first();

        return $paymentData;    
    }

    public function sendPaymentPedingMail(Request $request)
    {
        try {
            $getAppointmenDetails = Appointments::with(['patients.user','doctorTimeSlot.doctor.user'])
            ->where('id',$request->id)
            ->first(['id','patient_ID',
                DB::raw('DATE_FORMAT(appointmentDate,"%M %d, %Y") as appointmentDate'),'amount','doctorTimeSlot_ID'
            ]);

            if(!empty($getAppointmenDetails))
            {
                $email = (!empty($getAppointmenDetails->patients->user)) ? $getAppointmenDetails->patients->user->email : '';

                if(!empty($email))
                {
                    dispatch(new PaymentPendingJob($email,$getAppointmenDetails));
                }
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function markPaymentDone(Request $request)
    {
        try {
            // update the db
            Appointments::updatePaymentStatus($request->appointment_id);

            $randon_string = str()->random();

            $paymentResponseData = [
                'appointment_ID' => $request->appointment_id,
                'order_id' => "order_$randon_string",
                'res_payment_id' => $randon_string,
                'transaction_id' => null,
                'method' => 'offline',
                'email' => $request->email,
                'phone' => $request->contact,
                'payment_signature' => null,
                'amount' => $request->amount,
                'currency' => 'INR',
                'status' => 'completed',
                'json_response' => null,
                'createdBy' => auth()->user()->id
            ];

            PaymentDetails::create($paymentResponseData);

            return response()->json(['success' => true, 'message' => 'Payment successful']);

        } catch (\Exception $e) 
        {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function downloadInvoice($fileName)
    {
        $filePath = "public/invoices/invoice_$fileName.pdf";

        if (!Storage::exists($filePath)) {
            abort(404, "Invoice not found.");
        }

        return Storage::download($filePath, "invoice_$fileName.pdf");
    }
}
