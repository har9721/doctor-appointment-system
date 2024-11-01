<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\DoctorTimeSlots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('patients.myAppointments',compact('myPendingAppointments','myConfirmedAppointments','myCancelledAppointments','to_date'));
    }

    public function makrAppointments(AppointmentRequest $request)
    {
        if($request->ajax())
        {
            $updteStatus = $this->appoinments->markAppointment($request->all());

            if($updteStatus != null)
            {
                $response['status'] = 'success';
                $response['message'] = "Appointment ". $request['status']. " successfully.";
            }else{
                $response['status'] = 'success';
                $response['message'] = "Appointment not ".$request['status']." successfully.";
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = "Something went wrong";
        }

        return response()->json($response);
    }

    public function getAppointmentsDetails(AppointmentRequest $request)
    {
        if($request->ajax())
        {
            $appoinment_id = $request->appointment_id;
            $appointment_date = date('Y-m-d',strtotime($request->appointment_date));

            $getAppointmentData = Appointments::with(['doctorTimeSlot'])
                                ->where('id',$appoinment_id)
                                ->first(['id','doctorTimeSlot_ID','patient_ID',DB::raw('DATE_FORMAT(appointmentDate,"%d-%m-%Y") as appointmentDate'),'status','isBooked','isCancel','created_at']);

            $doctor_id = $getAppointmentData->doctorTimeSlot->doctor_ID;

            $availableTimeSlots = DoctorTimeSlots::where('doctor_ID',$doctor_id)
                                // ->where('id','!=',$getAppointmentData->doctorTimeSlot_ID)
                                ->where('availableDate',$appointment_date)
                                ->get(['id','doctor_ID','start_time','end_time']);
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
}
