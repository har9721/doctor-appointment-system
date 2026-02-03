<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Appointments extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    public $timestamps = false;

    protected $fillable = ['doctorTimeSlot_ID','status','patient_ID','appointmentDate','created_at','originalAppointmentDate','appointment_reminder_time','isRescheduled','archived_reason','createdBy', 'reason', 'appointment_no','amount','payment_status','isBooked','isCancel', 'advance_amount'];

    public function bookPatientAppointment($data)
    {
        $appointment = new Appointments();

        $appointment->doctorTimeSlot_ID = $data['timeSlot'];
        $appointment->patient_ID = $data['patient_ID'];
        $appointment->appointmentDate = date('Y-m-d',strtotime($data['date']));
        $appointment->originalAppointmentDate = date('Y-m-d',strtotime($data['date']));
        $appointment->appointment_reminder_time = true;
        $appointment->created_at = now();
        $appointment->createdBy = Auth::user()->id;
        $appointment->reason = $data['reason'] ?? null;
        $appointment->isBooked = 1;
        $appointment->amount = $data['consultationFees'];

        $appointment->save();

        return DoctorTimeSlots::updateIsBookTimeSlot($data,1);
    }

    public function doctorTimeSlot()
    {
        return $this->belongsTo(DoctorTimeSlots::class,'doctorTimeSlot_ID')->select('id','doctor_ID','availableDate','start_time','end_time',DB::raw('CONCAT_WS("-", DATE_FORMAT(doctor_time_slots.start_time, "%H:%i %P"), DATE_FORMAT(doctor_time_slots.end_time, "%H:%i %P")) as time'));    
    }

    public function patients()
    {
        return $this->belongsTo(Patients::class,'patient_ID')->select('id','user_ID');    
    }

    public function getAppointmentDateAttribute($value)
    {
        return date('d-m-Y',strtotime($value));
    }

    public static function getAppointmentList($from_date = null,$to_date  = null,$status = null, $appointment_no = null)
    {
        $getMyAppointments = Appointments::join('doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID')
        ->join('doctors', 'doctors.id', '=', 'doctor_time_slots.doctor_ID')
        ->join('patients', 'patients.id', '=', 'appointments.patient_ID')
        ->join('users as d', 'd.id', '=', 'doctors.user_ID')
        ->join('users as p', 'p.id', '=', 'patients.user_ID')
        ->join('mst_specialties', 'mst_specialties.id', '=', 'specialty_ID')
        // ->leftJoin('payment_details','payment_details.appointment_ID','appointments.id')
        ->leftJoin('prescriptions','prescriptions.appointment_ID','appointments.id')
        ->when((!empty($from_date)) || !empty($to_date), function($q) use($from_date, $to_date){     
            $q->whereBetween('appointmentDate', [
                date('Y-m-d', strtotime($from_date)),
                date('Y-m-d', strtotime($to_date))
            ]);
        })
        ->when($status === 'payment_pending', function($query) use($status){
            $query->where('appointments.payment_status','pending');
        }, function($query) use($status){
            ($status != 'all' && !empty($status)) ? $query->where('appointments.status',$status) : '';
        })
        ->where('appointments.isActive', 1)
        ->when(Auth::user()->role_ID == config('constant.doctor_role_ID'),function($query){
            $query->where('doctors.user_ID',Auth::user()->id);
        })
        ->when(Auth::user()->role_ID == config('constant.patients_role_ID'),function($query){
            $query->where('patients.user_ID',Auth::user()->id);
        })
        ->when(!empty($appointment_no), function($query) use($appointment_no){
            $query->where('appointments.appointment_no', 'like', '%' . $appointment_no . '%');
        })
        ->latest('appointment_type')
        ->get([
            'appointments.id',
            'appointments.doctorTimeSlot_ID',
            'appointments.patient_ID',
            DB::raw('DATE_FORMAT(appointments.appointmentDate,"%M %d, %Y") as appointmentDate'),
            'appointments.isBooked',
            'appointments.status',
            'appointments.created_at',
            'appointments.amount',
            'appointments.appointment_no',
            'appointments.payment_status',
            'mst_specialties.specialtyName',
            DB::raw('CONCAT_WS(" ", p.first_name, p.last_name) as patient_full_name'),                
            DB::raw('CONCAT_WS(" ", d.first_name, d.last_name) as doctor_full_name'),
            DB::raw('CONCAT_WS("-", DATE_FORMAT(doctor_time_slots.start_time, "%h:%i %p"), DATE_FORMAT(doctor_time_slots.end_time, "%h:%i %p")) as time'),'doctor_time_slots.doctor_ID','prescriptions.id as prescriptions_ID',
            'p.email as patient_email', 'p.mobile as patient_contact',
            DB::raw('
                CASE
                    WHEN appointments.appointmentDate < CURDATE() THEN "past"
                    WHEN appointments.appointmentDate = CURDATE() THEN "today"
                    ELSE "upcoming"
                    END as appointment_type
            '),
            'appointments.advance_amount',
            DB::raw('
                CASE
                    WHEN appointments.payment_status = "pending" THEN (appointments.amount - appointments.advance_amount)
                    WHEN appointments.payment_status = "partial" THEN (appointments.amount - appointments.advance_amount)
                    ELSE "0.00"
                    END as balance
            ')
        ]);

        return $getMyAppointments;
    }

    public function markAppointment($data)
    {
        $appointments = Appointments::find($data['appointment_id']);
        $appointments->status = $data['status'];
        // $appointments->status = ($data['status'] == 'archived') ? 'pending' : $data['status'];
        $appointments->archived_reason = (!empty($data['reason'])) ? $data['reason'] : null;
        $appointments->isCancel = ($data['status'] == 'cancelled') ? 1 : 0;
        $appointments->updated_at = now();
        $appointments->updatedBy = Auth::user()->id;
        
        return $appointments->update();
      
    }

    public static function getEmailData($appointments)
    {
        $doctorDetails = Doctor::join('doctor_time_slots', 'doctor_time_slots.doctor_ID', 'doctors.id')
        ->join('mst_specialties', 'mst_specialties.id', 'doctors.specialty_ID')
        ->join('users', 'users.id', 'doctors.user_ID')
        ->where('doctor_time_slots.id', $appointments->doctorTimeSlot_ID)
        ->first(['users.first_name', 'users.last_name', 'users.email', 'doctor_time_slots.start_time', 'doctor_time_slots.end_time','mst_specialties.specialtyName']);

        $emailData['doctor_name'] = $doctorDetails ? $doctorDetails->first_name . ' ' . $doctorDetails->last_name : null;
        $emailData['doctor_email'] = $doctorDetails ? $doctorDetails->email : null;
        $emailData['date'] = $appointments->appointmentDate;
        $emailData['time'] = $doctorDetails ? $doctorDetails->start_time : null;
        $emailData['isRescheduled'] = $appointments->isRescheduled;
        $emailData['amount'] = $appointments->amount;
        $emailData['specialty'] = $doctorDetails ? $doctorDetails->specialtyName : null;
        $emailData['payment_status'] = $appointments->payment_status;
        $emailData['appointment_no'] = $appointments->appointment_no;

        if (Auth::user()->role_ID == config('constant.doctor_role_ID') || Auth::user()->role_ID == config('constant.admin_role_ID')) {
            $appointments->load(['patients.user']);

            $emailData['patientsName'] = $appointments->patients ? $appointments->patients->user->first_name . ' ' . $appointments->patients->user->last_name : null;
            $emailData['patientsEmail'] = $appointments->patients ? $appointments->patients->user->email : null;
        } else {
            $patientsDetails = User::where('id', Auth::user()->id)->first(['first_name', 'last_name', 'email']);

            $emailData['patientsName'] = $patientsDetails ? $patientsDetails->first_name . ' ' . $patientsDetails->last_name : null;
            $emailData['patientsEmail'] = $patientsDetails ? $patientsDetails->email : null;
        }

        return $emailData;
    }

    public static function markIsRescheduleAppointment($data)
    {
        $appointments = Appointments::find($data['appointment_id']);
        $appointments->isRescheduled = 1;
        $appointments->status = 'pending';
        $appointments->doctorTimeSlot_ID = $data['new_time_slot'];
        $appointments->appointmentDate = date('Y-m-d',strtotime($data['appointment_date']));
        $appointments->updated_at = now();
        $appointments->updatedBy = Auth::user()->id;

        return $appointments->update();
    }

    public static function updateAmount($data)
    {
        return Appointments::where('id',$data['appointment_id'])
            ->update([
                'amount' => $data['amount'],
                'updated_at' => now(),
                'updatedBy' => Auth::user()->id
            ]);    
    }

    public static function updatePaymentStatus($id)
    {
        $appointment = Appointments::findOrFail($id);

        $appointment->payment_status = 'completed';
        $appointment->updated_at = now();
        $appointment->updatedBy = Auth()->user()->id;
        $appointment->update();

        return Patients::updatePaymentStatus($appointment->patient_ID,0);
    }

    public function paymentDetails()
    {
        return $this->hasOne(PaymentDetails::class,'appointment_ID');    
    }

    public static function getPaymentSummary($appointment_id)
    {
        $getPaymentSummary = PaymentDetails::with(['appointments.patients.user','appointments.doctorTimeSlot.doctor.user'])
        ->where('appointment_ID',$appointment_id)
        ->get();

        $paymentSummary = $getPaymentSummary->map(function($details){

            return [
                'payment_status' => $details->appointments->payment_status,
                'patientName' => $details->appointments->patients->user->full_name,
                'email' => $details->appointments->patients->user->email,
                'mobile' => $details->appointments->patients->user->mobile,
                'doctorName' => $details->appointments->doctorTimeSlot->doctor->user->full_name,
                'doctorEmail' => $details->appointments->doctorTimeSlot->doctor->user->email,
                'appointmentDate' => date('d-m-Y',strtotime($details->appointments->appointmentDate)),
                'time' => $details->appointments->doctorTimeSlot->time,
                'transaction_id' => $details->res_payment_id,
                'paymentDate' => date('d-m-Y',strtotime($details->created_at)),
                'amount' => $details->appointments->amount,
                'method' =>  $details->method,
                'appointment_no' => $details->appointments->appointment_no
            ];
        });

        return $paymentSummary;
    }

    public static function checkForOutstandingPayments($patients_id)
    {
        return Appointments::where('patient_ID',$patients_id)
            ->where('status','completed')
            ->where('payment_status','pending')
            ->exists();
    }

    public static function checkForAppointments($patients_id,$date = null)
    {
        return Appointments::join('doctor_time_slots','doctor_time_slots.id','appointments.doctorTimeSlot_ID')
            ->where('patient_ID',$patients_id)
            ->where('appointmentDate',date('Y-m-d',strtotime($date)))
            ->where('isActive',1)
            ->get(['appointments.id','appointments.doctorTimeSlot_ID','doctor_time_slots.start_time','doctor_time_slots.availableDate','doctor_time_slots.end_time']);
    }

    public static function updateAppointmentsDetails($data)
    {
        $appointment = Appointments::findOrFail($data['appointment_id']);
        
        $appointment->doctorTimeSlot_ID = $data['timeSlot'];
        $appointment->patient_ID = $data['patient_ID'];
        $appointment->appointmentDate = date('Y-m-d',strtotime($data['date']));
        $appointment->updated_at = now();
        $appointment->updatedBy = Auth()->user()->id;

        return $appointment->update();
    }

    public function prescriptions()
    {
        return $this->hasOne(Prescriptions::class,'appointment_ID')->select('id','appointment_ID','doctor_ID','patient_ID','medicines','instructions');    
    }

    public static function getPreviousTimeSlotID($id)
    {
        return Appointments::where('id',$id)->first(['doctorTimeSlot_ID as timeSlot']);
    }

    public function getAppointmentCount($status = null, $month = null, $payment_status = null,$roleName = null)
    {
        return Appointments::where('isActive',1)  
            ->when($roleName == 'Patients', function($query){
                $getPatientID = Patients::getLoginPatientsId();
                $query->where('patient_ID',$getPatientID->id);
            })
            ->when($status, function($query) use($status){
                $query->where('status', $status);
            })
            ->when($month, function($query) use($month){
                $query->whereMonth('appointmentDate', $month);
            })
            ->when($payment_status, function($query) use($payment_status){
                $query->where('payment_status', $payment_status);
            })
            ->count(); 
    }

    public function getDoctorDashboardData($status = null, $month = null, $payment_status = null)
    {
        $getDoctorID = Doctor::getLoginDoctorID();

        return Appointments::join(
            'doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID'
        )
        ->where('doctor_time_slots.doctor_ID', $getDoctorID->id)
        ->where('appointments.isActive', 1)
        ->when($status, function($query) use($status){
            $query->where('appointments.status', $status);
        })
        ->when($month, function($query) use($month){
            $query->whereMonth('appointments.appointmentDate', $month);
        })
        ->when($payment_status, function($query) use($payment_status){
            $query->where('appointments.payment_status', $payment_status);
        })
        ->count();
    }

    public static function getAppointmentsSum()
    {
        return Appointments::where('isActive',1)
            ->when(Auth::user()->isDoctor(), function($query){
                $getDoctorID = Doctor::getLoginDoctorID();

                $query->join(
                    'doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID'
                )
                ->where('doctor_time_slots.doctor_ID', $getDoctorID->id);
            })
            ->whereYear('appointmentDate', date('Y'))
            ->groupBy(DB::raw('MONTH(appointmentDate)'))
            ->select(DB::raw('SUM(amount) as total_amount, MONTHNAME(appointmentDate) as month'))
            ->get();
    }

    public static function getPieChartData()
    {
        return Appointments::join('doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID')
            ->join('doctors', 'doctors.id', '=', 'doctor_time_slots.doctor_ID')
            ->join('users as d', 'd.id', '=', 'doctors.user_ID')
            ->when(Auth::user()->isDoctor(), function($query){
                $getDoctorID = Doctor::getLoginDoctorID();

                $query->where('doctor_time_slots.doctor_ID', $getDoctorID->id);
            })
            ->where([
                'appointments.isActive' => 1,
                'doctors.isActive' => 1,
                'doctor_time_slots.isDeleted' => 0,
                'appointments.status' => 'completed',
            ])
            ->groupBy('doctor_time_slots.doctor_ID')
            ->select(
                DB::raw('COUNT(appointments.id) as total_appointments'),  
                DB::raw('CONCAT_WS(" ", d.first_name, d.last_name) as name'),
            )
            ->get();
    }

    public static function getUpcomingAppointments($status)
    {
        return Appointments::join(
            'doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID'
        )
        ->join('doctors', 'doctors.id', '=', 'doctor_time_slots.doctor_ID')
        ->join('users as d', 'd.id', '=', 'doctors.user_ID')
        ->join('patients', 'patients.id', '=', 'appointments.patient_ID')
        ->join('users as p', 'p.id', '=', 'patients.user_ID')
        ->when(Auth::user()->isDoctor(), function ($query){
            $doctor_ID = Doctor::getLoginDoctorID();

            $query->where('doctor_time_slots.doctor_ID', $doctor_ID->id);
        })
        ->when(Auth::user()->isPatients(), function($query)
        {
            $patients_Id = Patients::getLoginPatientsId();

            $query->where('appointments.patient_ID', $patients_Id->id);
        })
        ->where('appointments.isActive', 1)
        ->where('appointments.status', $status)
        ->when($status == 'completed', function($query){
            $query->where('appointments.appointmentDate', '<=', date('Y-m-d'));
        }, function($query){
            $query->where('appointments.appointmentDate', '>=', date('Y-m-d'));
        })
        ->where('appointments.payment_status', $status)
        ->limit(5)
        ->orderBy('appointments.appointmentDate','asc')
        ->select(
            DB::raw('CONCAT_WS(" ", p.first_name, p.last_name) as patient_full_name'),  
            DB::raw('CONCAT_WS(" ", d.first_name, d.last_name) as doctor_full_name'),
            DB::raw('CONCAT_WS("-", DATE_FORMAT(doctor_time_slots.start_time, "%h:%i %p"), DATE_FORMAT(doctor_time_slots.end_time, "%h:%i %p")) as time'), 
            DB::raw('DATE_FORMAT(appointments.appointmentDate,"%d-%m-%Y") as appointmentDate'),  
            'appointments.status','appointments.payment_status'    
        )
        ->get();
    }

    public static function getPatientOverview($status)
    {
        $getDoctorID = Doctor::getLoginDoctorID();

        return  Appointments::join(
            'doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID'
        )
        ->join('doctors', 'doctors.id', '=', 'doctor_time_slots.doctor_ID')
        ->join('patients', 'patients.id', '=', 'appointments.patient_ID')
        ->join('users as p', 'p.id', '=', 'patients.user_ID')
        ->where('appointments.isActive', 1)
        ->where('appointments.status', $status)
        ->where('doctor_time_slots.doctor_ID', $getDoctorID->id)
        ->where('patients.isActive', 1)
        ->where('patients.isDeleted', 0)
        ->groupBy('appointments.patient_ID')
        ->select(
            DB::raw('COUNT(appointments.id) as total_appointments'),
            DB::raw('CONCAT_WS(" ", p.first_name, p.last_name) as name'),
        )
        ->get();
    }

    // set appointment reminder time
    protected function appointmentReminderTime() : Attribute
    {
        return Attribute::make(
            get : fn($value) => $value ? date('d-m-Y H:i:s', strtotime($value)) : null,
            set : function($value, $attributes){
                $time_slot = DoctorTimeSlots::find($attributes['doctorTimeSlot_ID']);

                $appointmentTime = date(
                    'Y-m-d H:i:s', 
                    strtotime($attributes['appointmentDate'] . ' ' . $time_slot->start_time)
                );

                $reminderTime = date(
                    'Y-m-d H:i:s',
                    strtotime($appointmentTime . ' -1 hour')
                );

                return $reminderTime;
            } 
        );
    }

    public function tredsReportData($dates)
    {
        $appointmentData = Appointments::where([
            'isActive' => 1,
        ])
        ->when(Auth::user()->isDoctor(), function($query) {
            $doctor_ID = Doctor::getLoginDoctorID();

            $query->whereHas('doctorTimeSlot', function($query) use($doctor_ID){
                return $query->where('doctor_ID' ,$doctor_ID->id)
                        ->where('isDeleted',0);
            });
        })
        ->whereBetween('appointmentDate',$dates)
        ->select(
            DB::raw('DATE_FORMAT(appointmentDate, "%M-%Y") as showLabel'),
            DB::raw('YEAR(appointmentDate) as year'),
            DB::raw('MONTH(appointmentDate) as months'),
            DB::raw('COUNT(id) as total_appointment'),
            DB::raw('COUNT(CASE WHEN status = "Completed" THEN 1 END) As completed'),
            DB::raw('COUNT(CASE WHEN status = "Confirmed" THEN 1 END) As confirmed'),
            DB::raw('COUNT(CASE WHEN status = "Pending" THEN 1 END) As pending'),
            DB::raw('COUNT(CASE WHEN status = "cancelled" THEN 1 END) As cancelled')
        )
        ->groupBy('months')
        ->orderBy('year','asc')
        ->orderBy('months','asc')
        ->get();

        return $appointmentData;
    }

    public function timePreferenceData()
    {
        return Appointments::join('doctor_time_slots','doctor_time_slots.id','appointments.doctorTimeSlot_ID')
            ->where('appointments.isActive',1)
            ->where('doctor_time_slots.isDeleted',0)    
            ->where('doctor_time_slots.status','available')    
            ->where('doctor_time_slots.isBooked',1)
            ->when(auth()->user()->role->roleName === 'Doctor', function($query){
                $doctor_ID = Doctor::getLoginDoctorID();

                return $query->where('doctor_time_slots.doctor_ID',$doctor_ID->id);
            })
            ->select(
                DB::raw('CONCAT_WS("-", DATE_FORMAT(doctor_time_slots.start_time, "%h:%i %p"), DATE_FORMAT(doctor_time_slots.end_time, "%h:%i %p")) as time'),
                DB::raw('COUNT(doctor_time_slots.id) as timeCount'),
                'doctor_time_slots.doctor_ID'
            )
            ->groupBy('time')  
            ->orderBy('timeCount','desc')
            ->get();  
    }

    public function getAppointmentDetails($doctor_ID, $status, $start, $end)
    {
        return Appointments::with([
                'patients.user',
                'doctorTimeSlot'
            ])
            ->whereHas('doctorTimeSlot', function($query) use($doctor_ID){
                return $query->where('doctor_ID' ,$doctor_ID)
                        ->where('isDeleted',0);
            })
            ->when(!empty($start) && !empty($end), function($query) use($start, $end){
                $query->whereBetween('appointmentDate', [
                    $start,
                    $end
                ]);
            })
            ->where([
                'isActive' => 1,
                'status' => ucfirst($status)
            ])
            ->orderBy('id', 'desc')
            ->get(['id','doctorTimeSlot_ID','patient_ID','appointmentDate']);
    }

    // protected function appointmentDate() : Attribute 
    // {
    //     return  Attribute::make(
    //         get: fn($value) => Carbon::parse($value)->format('d-m-Y'),
    //     );
    // }

    public function fetchPatientsHistory($data)
    {
        $doctor_ID = Doctor::getLoginDoctorID();

        return Appointments::with([
            'patients.user',
            'paymentDetails:id,appointment_ID,res_payment_id,method,created_at',
            'prescriptions.doctor.user',
            'doctorTimeSlot' =>  function($query)
            {
                return $query->where('isDeleted', 0);
            }
        ])
        ->when(auth()->user()->role->roleName == 'Patients', function($query){
            $patients_Id = Patients::getLoginPatientsId();

            $query->whereHas(
                'prescriptions', function($query) use($patients_Id){
                    return $query->where([
                        'patient_ID' => $patients_Id->id,
                        'isActive' => 1
                    ]);
                }
            );
        })
        ->when(auth()->user()->role->roleName == 'Doctor', function($query) use($doctor_ID){
            $query->whereHas(
                'prescriptions', function($query) use($doctor_ID){
                    return $query->where([
                        'doctor_ID' => $doctor_ID->id,
                        'isActive' => 1
                    ]);
                }
            );
        })
        ->where('isActive', 1)
        ->when(!empty($data['start_date'] && !empty($data['end_date'])), function($query) use($data){
            $startDate = date('Y-m-d', strtotime($data['start_date']));
            $toDate = date('Y-m-d', strtotime($data['end_date']));

            return $query->whereBetween('appointmentDate',[$startDate, $toDate]);
        })
        ->when(!empty($data['id']), function($query) use($data) {
            return $query->whereIn('patient_ID', $data['id']);
        })
        ->get([
            'id',
            'patient_ID',
            'status',
            'appointmentDate', 
            'doctorTimeSlot_ID',
            'reason',
            'appointment_no',
            'payment_status',
            'amount'
        ]);
    }
}
