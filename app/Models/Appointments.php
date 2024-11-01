<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Appointments extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    public $timestamps = false;

    protected $fillable = ['doctorTimeSlot_ID','status','patient_ID','appointmentDate','created_at','originalAppointmentDate','isRescheduled'];

    public static function bookPatientAppointment($data)
    {
        Appointments::create([
            'doctorTimeSlot_ID' => $data['timeSlot'],
            'patient_ID' => $data['patient_ID'],
            'appointmentDate' => date('Y-m-d',strtotime($data['date'])),
            'originalAppointmentDate' => date('Y-m-d',strtotime($data['date'])),
            'created_at' => now(),
            'createdBy' => Auth::user()->id
        ]);

        return DoctorTimeSlots::updateIsBookTimeSlot($data);
    }

    public function doctorTimeSlot()
    {
        return $this->belongsTo(DoctorTimeSlots::class,'doctorTimeSlot_ID')->select('id','doctor_ID','availableDate','start_time','end_time');    
    }

    public function patients()
    {
        return $this->belongsTo(Patients::class,'patient_ID')->select('id','user_ID');    
    }

    public function getAppointmentDateAttribute($value)
    {
        return date('d-m-Y',strtotime($value));
    }

    public static function getAppointmentList($from_date,$to_date,$status)
    {
        $getMyAppointments = Appointments::join('doctor_time_slots', 'doctor_time_slots.id', '=', 'appointments.doctorTimeSlot_ID')
        ->join('doctors', 'doctors.id', '=', 'doctor_time_slots.doctor_ID')
        ->join('patients', 'patients.id', '=', 'appointments.patient_ID')
        ->join('users as d', 'd.id', '=', 'doctors.user_ID')
        ->join('users as p', 'p.id', '=', 'patients.user_ID')
        ->join('mst_specialties', 'mst_specialties.id', '=', 'specialty_ID')
        ->whereBetween('appointmentDate', [
            date('Y-m-d', strtotime($from_date)),
            date('Y-m-d', strtotime($to_date))
        ])
        ->when($status, function($query) use($status){
            $query->where('appointments.status',$status);
        })
        ->where('appointments.isActive', 1)
        ->when(Auth::user()->role_ID == config('constant.doctor_role_ID'),function($query){
            $query->where('doctors.user_ID',Auth::user()->id);
        },function($query){
            $query->where('patients.user_ID',Auth::user()->id);
        })
        ->latest('appointments.created_at')
        ->get([
            'appointments.id',
            'appointments.doctorTimeSlot_ID',
            'appointments.patient_ID',
            DB::raw('DATE_FORMAT(appointments.appointmentDate,"%M %d, %Y") as appointmentDate'),
            'appointments.isBooked',
            'appointments.status',
            'appointments.created_at',
            'mst_specialties.specialtyName',
            DB::raw('CONCAT_WS(" ", p.first_name, p.last_name) as patient_full_name'),                
            DB::raw('CONCAT_WS(" ", d.first_name, d.last_name) as doctor_full_name'),
            DB::raw('CONCAT_WS("-", DATE_FORMAT(doctor_time_slots.start_time, "%H:%i %P"), DATE_FORMAT(doctor_time_slots.end_time, "%H:%i %P")) as time'),
        ]);

        return $getMyAppointments;
    }

    public function markAppointment($data)
    {
        $appointments = Appointments::find($data['appointment_id']);
        $appointments->status = ($data['status'] == 'confirm') ? 'confirmed' : 'cancelled';
        $appointments->updated_at = now();
        $appointments->updatedBy = Auth::user()->id;
        
        return $appointments->update();
      
    }

    public static function getEmailData($appointments)
    {
        $doctorDetails = Doctor::join('doctor_time_slots', 'doctor_time_slots.doctor_ID', 'doctors.id')
        ->join('users', 'users.id', 'doctors.user_ID')
        ->where('doctor_time_slots.id', $appointments->doctorTimeSlot_ID)
        ->first(['users.first_name', 'users.last_name', 'users.email', 'doctor_time_slots.start_time', 'doctor_time_slots.end_time']);

        $emailData['doctor_name'] = $doctorDetails ? $doctorDetails->first_name . ' ' . $doctorDetails->last_name : null;
        $emailData['doctor_email'] = $doctorDetails ? $doctorDetails->email : null;
        $emailData['date'] = $appointments->appointmentDate;
        $emailData['time'] = $doctorDetails ? $doctorDetails->start_time : null;
        $emailData['isRescheduled'] = $appointments->isRescheduled;

        if (Auth::user()->role_ID == config('constant.doctor_role_ID')) {
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
}
