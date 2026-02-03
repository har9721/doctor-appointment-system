<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentBooking;
use App\Http\Requests\PaymentRequest;
use App\Jobs\PaymentPendingJob;
use App\Models\Appointments;
use App\Models\DoctorTimeSlots;
use App\Models\Patients;
use App\Models\PaymentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $keyId;
    private $secretKey;
    private $api;
    private $webhookSecret;
    public $paymentResponseData = [];

    public function __construct()
    {
        $this->keyId = config('services.razorpay.RAZORPAY_KEY_ID');
        $this->secretKey = config('services.razorpay.RAZORPAY_SECRET_KEY');
        $this->webhookSecret = config('services.razorpay.RAZORPAY_WEBHOOK_SECRET');
        $this->api = new Api($this->keyId,$this->secretKey);
    }

    public function viewPaymentPage($id, $column = 'amount')
    {
        try {
            $appointments = Appointments::find($id);
    
            $amountToPay = ($column == 'amount') ? ($appointments->amount - $appointments->advance_amount) : $appointments->advance_amount;

            $paymentData = [
                'receipt'         => uniqid(),
                'amount'          => $amountToPay * 100, // amount convert into paise (₹500)
                'currency'        => 'INR',
                'payment_capture' => 1
            ];

            $getPatientDetails = Patients::getPatientDetails($id);

            $patientsData = [
                'name' => $getPatientDetails->patient_full_name,
                'email' => $getPatientDetails->email,
                'contact' => $getPatientDetails->mobile,
                'notes' => [
                    'patient_id' => $appointments->patient_ID,
                    'appointment_id' => $appointments->id
                ]
            ];

            if(empty($getPatientDetails->razorpay_cust_id))
                $customer_id = $this->api->customer->create($patientsData);
            else
                $customer_id = $getPatientDetails->razorpay_cust_id;

            $razorPayResponse = $this->api->order->create($paymentData);

            // create new payment details record
            $paymentResponseData = [
                'appointment_ID' => $appointments->id,
                'order_id' => $razorPayResponse['id'],
                'transaction_id' => null,
                'payment_type' => ($column == 'advanceFees') ? 'advance' :'full_payment',
                'amount' => number_format($amountToPay,2),
                'status' => 'pending',
                'createdBy' => auth()->user()->id,
                'created_at' => now()
            ];

            $paymentDetails_ID = PaymentDetails::create($paymentResponseData);
            info('------------------paymentDetails_ID---------------------');info($paymentDetails_ID->id);

            return [
                'response' => $razorPayResponse->toArray(),
                'customer' => $customer_id,
                'userName' => $getPatientDetails->patient_full_name,
                'userEmail' => $getPatientDetails->email,
                'contact' => $getPatientDetails->mobile,
                'paymentDetails_ID' => $paymentDetails_ID->id
            ];

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'line' => $th->getLine()
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
            info('------------------inside handle payment function---------------------');info($request->all());
            
            // Validate that we have at least one required ID
            if(empty($request->razorpay_order_id) && empty($request->razorpay_payment_id)) {
                throw new \Exception('Either razorpay_order_id or razorpay_payment_id must be present.');
            }
            
            $this->api->utility->verifyPaymentSignature(array(
                'razorpay_order_id' => $request->razorpay_order_id, 
                'razorpay_payment_id' => $request->razorpay_payment_id, 
                'razorpay_signature' => $request->razorpay_signature
            ));

            // update the db
            if($request->isAdvance == 0)
                Appointments::updatePaymentStatus($request->appointment_id);

            // $getPatientDetails = Patients::getPatientDetails($request->appointment_id);

            // fetch the payment details using the payment id
            $fetchPaymentDetails = $this->api->payment->fetch($request->razorpay_payment_id);

            if(isset($request->payment_details_id) && !empty($request->payment_details_id))
            {
                info('------------------inside update payment details---------------------');
                // update payment details record
                $payment = PaymentDetails::findOrFail($request->payment_details_id);

                $payment->update([
                    'currency' => $request->currency,
                    'status'   => ($request->isAdvance == 1) ? 'partial' : 'completed',
                    'method'   => $fetchPaymentDetails->method,
                    'email'    => $request->email,
                    'phone'    => $request->contact,
                    'res_payment_id' => $fetchPaymentDetails->id,
                    'json_response'  => json_encode((array)$fetchPaymentDetails),
                ]);
            }
            else
            {
                info('------------------inside create new payment details---------------------');
                // create new payment details record
                $paymentResponseData = [
                    'appointment_ID' => $request->appointment_id,
                    'order_id' => $request->razorpay_order_id,
                    'res_payment_id' => $fetchPaymentDetails->id,
                    'transaction_id' => null,
                    'method' => $fetchPaymentDetails->method,
                    'email' => $request->email,
                    'phone' => $request->contact,
                    'payment_type' => 'full_payment',
                    'amount' => ($request->amount) ? $request->amount/100 : 0.00,
                    'currency' => $request->currency,
                    'status' => 'completed',
                    'json_response' => json_encode((array)$fetchPaymentDetails),
                    'createdBy' => auth()->user()->id
                ];
    
                PaymentDetails::create($paymentResponseData);
            }

            return response()->json(['success' => true, 'message' => 'Payment successful']);

        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage(), 'line' => $e->getLine()]);
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
        $payment_id = $request->payment_id;

        $paymentData = $this->paymentSummary($appointment_id, $payment_id);

        return view('payments.success',['payment' => $paymentData]);    
    }

    function paymentSummary($appointment_id, $payment_id = null)
    {
        if($payment_id)
        {
            return PaymentDetails::where('appointment_ID',$appointment_id)->where('id',$payment_id)->first();
        }

        return PaymentDetails::where('appointment_ID',$appointment_id)->get();
    }

    public function sendPaymentPedingMail(Request $request)
    {
        try {
            $getAppointmenDetails = Appointments::with(['patients.user','doctorTimeSlot.doctor.user'])
            ->where('id',$request->id)
            ->first(['id','patient_ID',
                DB::raw('DATE_FORMAT(appointmentDate,"%M %d, %Y") as appointmentDate'),'amount','doctorTimeSlot_ID',
                'appointment_no'
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
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'line' => $e->getLine()]);
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
                'payment_type' => 'offline',
                // 'payment_signature' => null,
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
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'line' => $e->getLine()]);
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

    public function makeAdvancePayment(AppointmentBooking $request)
    {        
        try {
            $patient_id = $request->patient_ID;
                
            DB::transaction(function ()  use($request, $patient_id, &$response){
                $time_slot_id = $request->timeSlot;
                    
                $appointment_date = date('Y-m-d', strtotime($request->date));
                $reason = trim($request->reason);
                $amount = $request->consultationFees;
                $advanceFees = $request->advanceFees;

                // Lock the timeslot
                $timeSlot = DoctorTimeSlots::where('id', $time_slot_id)
                    ->lockForUpdate()
                    ->first();

                if (!$timeSlot) {
                    throw new \Exception('Time slot not found');
                }

                if ($timeSlot->isBooked) {
                    throw new \Exception('This time slot is already booked');
                }

                $checkForOutstandingPayments = Appointments::checkForOutstandingPayments($patient_id);

                if (!empty($checkForOutstandingPayments)) {
                    throw new \Exception('Outstanding payment exists for this patient');
                }

                // update time slot as booked
                DoctorTimeSlots::where('id', $time_slot_id)
                ->update([
                    'isBooked' => 1,
                    'updatedBy' => auth()->user()->id,
                    'updated_at' => now()
                ]);

                 // book appointment
                $appointment = Appointments::create([
                    'patient_ID' => $patient_id,
                    'doctorTimeSlot_ID' => $time_slot_id,
                    'appointmentDate' => $appointment_date,
                    'originalAppointmentDate' => $appointment_date,
                    'reason' => $reason,
                    'amount' => $amount,
                    'advance_amount' => $advanceFees,
                    'status' => 'pending',
                    'payment_status' => 'partial',
                    'createdBy' => auth()->user()->id,
                    'created_at' => now()
                ]);

                $paymentData = $this->viewPaymentPage($appointment->id, 'advanceFees');
    
                // $paymentDetails = PaymentDetails::create([
                //     'appointment_ID' => $appointment->id,
                //     'order_id' => $paymentData['id'],
                //     'amount' => $advanceFees,
                //     'currency' => 'INR',
                //     'status' => 'pending',
                //     'payment_type' => 'advance',
                //     'createdBy' => auth()->user()->id
                // ]);

                $response = [
                    'appointment' => $appointment,
                    // 'paymentDetails' => $paymentDetails,
                    'paymentData' => $paymentData
                ];
            },3);

            DB::commit();

            // $getPatientDetails = Patients::with('user')->where('id',$patient_id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Advance payment successful',
                'paymentsData' => $response['paymentData'],
                'appointment_id' => $response['appointment']->id,
                'paymentDetails_id' => $response['paymentData']['paymentDetails_ID'],
                'userName' => $response['paymentData']['userName'],
                'userEmail' => $response['paymentData']['userEmail'],
                'contact' => $response['paymentData']['contact'],
                'isAdvance' => 1
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function razorpayWebhooks(Request $request)
    {
        info('------------------inside webhook function---------------------');
        $signature = $request->header('X-Razorpay-Signature');
        $paymentData = $request->getContent();

        if(!empty($paymentData))
        {
            $this->api->utility->verifyWebhookSignature(
                $paymentData,
                $signature,
                $this->webhookSecret
            );
    
            $event = json_decode($paymentData, true);;
    
            Log::info($event);

            if($event['event'] === 'payment.captured')
            {
                $paylod = $event['payload']['payment']['entity'];

                $description = $paylod['description'];

                // fetch the payment details using the payment id
                $fetchPaymentDetails = $this->api->payment->fetch($paylod['id']);

                $appointment_id = substr($description, strrpos($description, '_') + 1);
                info('appointment id');info($appointment_id);

                Appointments::where('id', $appointment_id)->update([
                    'payment_status' => 'completed',
                    'updated_at'    => now()
                ]);

                $order_Id = $paylod['order_id'];
                info('order_id');info($order_Id);

                $payment = PaymentDetails::findOrFail($appointment_id, $order_Id);

                $payment->update([
                    'status'   => 'completed',
                    'method'   => $paylod['method'],
                    'email'    => $paylod['email'],
                    'phone'    => $paylod['contact'],
                    'currency' => $paylod['currency'],
                    'res_payment_id' => $fetchPaymentDetails->id,
                    'json_response'  => json_encode((array)$fetchPaymentDetails),
                ]);

                info('-----------------------Payment updated successfully via webhook.---------------------');
            }

            if($event['event'] === 'payment.failed')
            {
                info('-----------------------Payment failed webhook received.---------------------');

                $paylod = $event['payload']['payment']['entity'];
                info('------failed payment------------');info($paylod);

                // fetch the payment details using the payment id
                $fetchPaymentDetails = $this->api->payment->fetch($paylod['id']);

                $order_Id = $paylod['order_id'];
                info('order_id');info($order_Id);
 
                $payment = PaymentDetails::where('order_id', $order_Id)->first();

                if(!empty($payment))
                {
                    $appointment_ID = $payment->appointment_ID;

                    $get_time_slot_id = Appointments::where('id', $appointment_ID)->value('doctorTimeSlot_ID');

                    if(!empty($get_time_slot_id))
                    {
                        // free the time slot
                        DoctorTimeSlots::where('id', $get_time_slot_id)
                        ->update([
                            'isBooked' => 0,
                            'updated_at' => now()
                        ]);
                    }
    
                    Appointments::where('id', $payment->appointment_ID)
                    ->update([
                        'payment_status' => 'failed',
                        'updated_at'    => now()
                    ]);
    
                    $payment->update([
                        'status'   => 'failed',
                        'method'   => $paylod['method'],
                        'email'    => $paylod['email'],
                        'phone'    => $paylod['contact'],
                        'currency' => $paylod['currency'],
                        'res_payment_id' => $fetchPaymentDetails->id,
                        'json_response'  => json_encode((array)$fetchPaymentDetails),
                    ]);
    
                    info('-----------------------Payment updated successfully via webhook.---------------------');
                }
            }
        }
    }
}