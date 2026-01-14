<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentBooking;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\DoctorTimeSlots;
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{
    protected $appoinments;

    public function __construct(Appointments $appoinments)
    {
       $this->appoinments = $appoinments; 
    }

    public function getAppointments(Request $request)
    {
        $from_date = (!empty($request->from_date)) ? $request->from_date : date('01-m-Y');
        $to_date = (!empty($request->to_date)) ? $request->to_date : date('d-m-Y',strtotime(date('t-m-Y')));
        $status = $request->status;

        $myPendingAppointments = Appointments::getAppointmentList($from_date,$to_date,'pending');
        $myConfirmedAppointments = Appointments::getAppointmentList($from_date,$to_date,'confirmed');
        $myCancelledAppointments = Appointments::getAppointmentList($from_date,$to_date,'cancelled');
        $completedAppointments = Appointments::getAppointmentList($from_date,$to_date,'completed');

        $heading = (Auth::user()->role->roleName === 'Admin' || Auth::user()->role->roleName === 'Doctor') ? 'Appointments' : 'My Appointments';

        return view('patients.myAppointments',compact('myPendingAppointments','myConfirmedAppointments','myCancelledAppointments','to_date','completedAppointments','heading','from_date'));
    }

    public function markAppointments(AppointmentRequest $request)
    {
        if($request->ajax())
        {
            $updteStatus = $this->appoinments->markAppointment($request->all());
            
            // update isbooked time slot
            if($request['status'] != 'archived')
            {
                DoctorTimeSlots::updateIsBookTimeSlot($request->all(),1);
            }

            // update has_payment_pending flag
            Patients::updatePaymentStatus($request['patient_ID'],0);

            $status = ($request['status'] == 'archived') ? 'archived' : $request['status'];

            if($updteStatus != null)
            {
                $response['status'] = 'success';
                $response['message'] = "Appointment $status successfully.";
            }else{
                $response['status'] = 'success';
                $response['message'] = "Appointment not $status successfully.";
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = "Something went wrong";
        }

        return response()->json($response);
    }

    public function getAppointmentsDetails(Request $request)
    {
        if($request->ajax())
        {
            $appoinment_id = $request->appointment_id;
            $appointment_date = date('Y-m-d',strtotime($request->appointment_date));

            $getAppointmentData = Appointments::with(['doctorTimeSlot.doctor.user'])
                                ->where('id',$appoinment_id)
                                ->first(['id','doctorTimeSlot_ID','patient_ID',DB::raw('DATE_FORMAT(appointmentDate,"%d-%m-%Y") as appointmentDate'),'status','isBooked','isCancel','created_at']);

            $doctor_id = $getAppointmentData->doctorTimeSlot->doctor_ID;

            $availableTimeSlots = DoctorTimeSlots::where('doctor_ID',$doctor_id)
                                // ->where('id','!=',$getAppointmentData->doctorTimeSlot_ID)
                                ->where('availableDate',$appointment_date)
                                // ->where('isBooked',0)
                                ->orderBy('start_time','asc')
                                ->get(['id','doctor_ID','start_time','end_time','isBooked']);
            $data = [
                'availableTimeSlot' => $availableTimeSlots,
                'bookedSlot' => $getAppointmentData
            ];

            return $data;
        }else
        {
            return null;
        }   
    }

    public function getDoctorAvailableTime(AppointmentRequest $request)
    {
        $request->validate([
            'timeSlot' => $request->routeIs('appointments.fetch-time-slot') ? 'nullable' : 'required',
        ]);

        $appointment_date = date('Y-m-d',strtotime($request->appointment_date));
        $doctor_id = $request->doctor_ID;

        $getAvailableTimeSlotForDate = Doctor::with(['timeSlot' => function($q) use($appointment_date){
            $q->where('availableDate',$appointment_date)->where('isBooked',0);
        }])
        ->where('id',$doctor_id)
        ->first(['id','specialty_ID']);
        
        return $getAvailableTimeSlotForDate;
    }

    public function rescheduleAppointment(AppointmentRequest $request)
    {
        if($request->ajax())
        {
            $markIsReschedule = Appointments::markIsRescheduleAppointment($request->all());

            $request->merge([
                'timeSlot' => $request->doctorTimeSlot_ID,
            ]);

            $getPreviousTimeSlotID = Appointments::getPreviousTimeSlotID($request['appointment_id']);

            // update previous time slot isBooked = 0
            if(!empty($getPreviousTimeSlotID))
            {
                DoctorTimeSlots::updateIsBookTimeSlot($getPreviousTimeSlotID,0);
            }

            DoctorTimeSlots::updateIsBookTimeSlot($request->all(),1);

            if($markIsReschedule != null)
            {
                $response['status'] = 'success';
                $response['message'] = "Appointment reschedule successfully.";
            }else{
                $response['status'] = 'success';
                $response['message'] = "Appointment not reschedule successfully.";
            }
        }else{
            $response['status'] = "error";
            $response['message'] = "something went wrong.";
        }

        return response()->json($response);
    }

    public function saveAmount(Request $request)
    {
        try {
            $validated = Validator::make($request->all(),[
                'appointment_id' => 'required',
                'amount' => 'required|numeric|gt:0'
            ]);

            if($validated->fails())
            {
                $response['status'] = 'error';
                $response['message'] = $validated->errors()->first('amount');
            }else{
                $updateAmount = Appointments::updateAmount($request->all());

                if($updateAmount != null)
                {
                    $response['status'] = 'success';
                    $response['message'] = "Fees added successfully.";
                }else{
                    $response['status'] = 'success';
                    $response['message'] = "Fees not added successfully.";
                }
            }
        } catch (\Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = "Something went wrong.";
        }

        echo json_encode($response);
    }

    public function viewCompletedList()
    {
        return view('patients.completedAppointmentList');    
    }

    public function getCompletedAppointment(Request $request)
    {
        $from_date = (!empty($request->from_date)) ? $request->from_date : date('01-m-Y');
        $to_date = (!empty($request->to_date)) ? $request->to_date : date('d-m-Y',strtotime(date('t-m-Y')));
        $status = (!empty($request->status)) ? $request->status : "";
        $appoinmentno = $request->appointment_no ?? '';

        $completedAppointments = Appointments::getAppointmentList($from_date,$to_date,$status, $appoinmentno);
        
        return DataTables::of($completedAppointments)
            ->addIndexColumn()
            ->editColumn('action', function($row){
                $viewPaymentSummay = ($row['payment_status'] == 'completed') ? '<button name="Pay" class="mr-2 btn btn-sm btn-info border text-white payment_summary"  data-toggle="tooltip" data-id = "'.$row['id'].'" data-amount = "'. $row['amount'] .'" data-placement="bottom" title="View Payment Summary"  data-bs-toggle="modal" data-bs-target="#paymentSummaryModal">
                    <i class="fas fa-file-invoice-dollar"></i> 
                </button>' : '' ;

                $viewPrescriptions = (isset($row['prescriptions_ID']) && !empty($row['prescriptions_ID'])) ? '<button name="View Prescriptions" class="mr-2 btn btn-sm btn-info border text-white prescription_summary"  data-toggle="tooltip" data-prescriptions_id = "'.$row['prescriptions_ID'].'" data-placement="bottom" title="View Prescriptions"  data-bs-toggle="modal" data-bs-target="#paymentSummaryModal">
                    <i class="fas fa-receipt"></i> 
                </button>' : '' ;

                $pay = ($row['payment_status'] == 'pending' && ($row['status'] == 'completed' || $row['status'] == 'confirmed') && (!in_array(Auth::user()->role_ID, config('constant.admin_and_doctor_role_ids')))
                && $row['amount'] != null
                ) 
                ? '<button name="Pay" id="payment" class="mr-2 payment btn btn-sm success border text-white bg-dark" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Pay" data-email ="' . $row['patient_email'] . '" data-contact ="' . $row['patient_contact'] . '" data-name="'. $row['patient_full_name'] .'">
                    <i class="fas fa-credit-card"></i> Pay Now
                </button>' 
                : '';

                // $filePath = 'public/invoices/invoice_' . $row['transaction_id'] . '.pdf';

                $downloadInvoice = ($row['payment_status'] == 'completed' && (!empty($row['res_payment_id']))) ? 
                '<a href="'. route("payments.download-invoice",['link' => $row['res_payment_id']]) .'">
                <button name="invoice" class="mr-2 btn btn-sm btn-dark border text-white download_invoice"  data-toggle="tooltip" data-id = "'.$row['id'].'" data-amount = "'. $row['amount'] .'" data-placement="bottom" title="Download Payment Summary"  data-bs-toggle="modal">
                    <i class="fas fa-download"></i> 
                </button></a>' : '' ;

                $downloadPrescriptions = (isset($row['prescriptions_ID']) && !empty($row['prescriptions_ID'])) ? 
                '<a href="'. route("appointments.prescription-download",['id' => $row['prescriptions_ID']]) .'">
                <button name="prescriptions" class="mr-2 btn btn-sm btn-dark border text-white download_prescriptions"  data-toggle="tooltip" data-prescriptions_id = "'.$row['prescriptions_ID'].'" data-placement="bottom" title="Download Prescriptions Summary"  data-bs-toggle="modal">
                    <i class="fas fa-download"></i> 
                </button></a>' : '' ;

                if(
                    in_array(Auth::user()->role_ID, config('constant.admin_and_doctor_role_ids')) 
                    &&
                    $row['status'] == 'completed' 
                    &&
                    $row['payment_status'] == 'pending'
                )
                {
                    $sendMail = '<button name="send_mail" id="send_mail" class="mr-2 sendMail btn btn-sm border text-white bg-dark" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Send Payment Mail">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </button>';

                    $markPayment = '<button name="mark_payment" id="mark_pay_done" class="mr-2 markPayDone btn btn-sm border text-white bg-dark payment" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Mark Payment Done">
                        <i class="fas fa-check"></i>
                    </button>';
                }else{
                    $sendMail = '';
                    $markPayment = '';
                }

                return $viewPaymentSummay.$pay.$sendMail.$markPayment.$downloadInvoice.$viewPrescriptions.$downloadPrescriptions;
            })
            ->editColumn('status', function($row){
                if($row['status'] === 'pending')
                {
                    $color = '<label class="badge bg-warning text-white">'.ucfirst($row['status']).'</label>';
                }else if($row['status'] === 'completed' || $row['status'] === 'confirmed')
                {
                    $color = '<label class="badge bg-success text-white">'.ucfirst($row['status']).'</label>';
                }else if($row['status'] === 'cancelled')
                {
                    $color = '<label class="badge bg-danger text-white">'.ucfirst($row['status']).'</label>';
                }else
                {
                    $color = '<label class="badge .bg-secondary text-white">'.ucfirst($row['status']).'</label>';
                }

                return $color;
            })
            ->editColumn('payment_status', function($row){
                return ($row['payment_status'] == 'pending') ? '<label class="badge bg-warning text-white">'.ucfirst($row['payment_status']).'</label>' : '<label class="badge bg-success text-white">'.ucfirst($row['payment_status']).'</label>';
            })
            ->rawColumns(['action','status','payment_status'])
            ->make(true);
    }

    public function getAppointmentDetails(Request $request)
    {
        if($request->ajax())
        {
            $appointment_id = $request->appointment_id;

            $getAppointmentDetails = Appointments::with(['doctorTimeSlot' => function($q){
                $q->with(['doctor.user']);
            }])
            ->where('id',$appointment_id)
            ->first(['id','doctorTimeSlot_ID','patient_ID','amount','status','isBooked','isCancel','created_at',DB::raw('DATE_FORMAT(appointments.appointmentDate,"%M %d, %Y") as appointmentDate')]);

            if(!in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids')))
            {
                $paymentData = (new PaymentController)->viewPaymentPage($appointment_id);
            }else{
                $paymentData = '';
            }
            
            return [
                'paymentsData' => ($paymentData) ? (array)$paymentData : '',
                'getApointmentDetails' => $getAppointmentDetails,
            ];
        }else{
            return null;
        }    
    }

    public function viewAppointmentHistory()
    {
        return view('admin.viewAppointmentHistory');    
    }

    public function updateAppointments(AppointmentBooking $request)
    {
        try {
            $data = $request->validated();

            $getPreviousTimeSlotID = Appointments::getPreviousTimeSlotID($data['appointment_id']);

            // update previous time slot isBooked = 0
            if(!empty($getPreviousTimeSlotID))
            {
                DoctorTimeSlots::updateIsBookTimeSlot($getPreviousTimeSlotID,0);
            }
    
            $updateAppointmentsDetails = Appointments::updateAppointmentsDetails($data);

            DoctorTimeSlots::updateIsBookTimeSlot($request->all(),1);

            if($updateAppointmentsDetails != null)
            {
                $response['status'] = 'success';
                $response['message'] = "Appointments details updated successfully.";
            }else{
                $response['status'] = 'success';
                $response['message'] = "Appointments details not updated successfully.";
            }

        } catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
    }
}
