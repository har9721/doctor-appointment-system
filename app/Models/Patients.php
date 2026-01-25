<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Patients extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'patients';

    public $timestamps = false;

    protected $fillable = ['user_ID','created_at'];

    public static function addPatients($data)
    {
        $addPatients = Patients::create([
            'user_ID' => $data['user_ID'],
            'created_at' => now(),
        ]);

        if(!empty($addPatients))
        {
            $data['patient_ID'] = $addPatients->id;
            $data['role'] = config('constant.patients_role_ID');

            // save other information of patients
            PatientsEmergencyContacts::addEmergencyContacts($data);
            PatientsMedicalHistory::insertMedicalHistory($data);
            PatientsLifeStyleInformation::addLifeStyleInformation($data);
        }

        return (!empty($addPatients)) ? $addPatients->id : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_ID')->where('isActive',1)->select('id','first_name','last_name','email','mobile','age','address','city_ID','gender_ID','isActive',DB::raw('CONCAT_WS(" ", first_name, last_name) as patients_name'));
    }

    public static function getLoginPatientsId()
    {
        return Patients::where('user_ID',Auth::user()->id)->first('id');
    }

    public function emergencyContact()
    {
        return $this->hasOne(PatientsEmergencyContacts::class,'patient_ID')->where('isActive',1)->select('id','patient_ID','contact_name','contact_relation','phone_no',);    
    }

    public function lifeStyleInformation()
    {
        return $this->hasOne(PatientsLifeStyleInformation::class,'patient_ID')->where('isActive',1)->select('id','patient_ID','smokingStatus_ID','alcoholStatus_ID','exercise');    
    }

    public function medicalHistory()
    {
        return $this->hasOne(PatientsMedicalHistory::class,'patient_ID')->where('isActive',1)->select('id','patient_ID','illness','surgery','allergies','chronicDisease','medication');    
    }

    public static function updatePaymentStatus($id,$pending_payment)
    {
        return Patients::where('id',$id)->update([
            'has_pending_payment' => $pending_payment,
            'updatedBy' => Auth::user()->id,
            'updated_at' => now()
        ]);
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class,'patient_ID')->where('isActive',1)->select(['id','doctorTimeSlot_ID','patient_ID']);
    }

    public static function getPatientsList()
    {
        return Patients::leftJoin('appointments','appointments.patient_ID','patients.id')
        ->leftJoin('doctor_time_slots','doctor_time_slots.id','appointments.doctorTimeSlot_ID')
        ->leftJoin('doctors','doctors.id','doctor_time_slots.doctor_ID')
        ->leftJoin('users','users.id','patients.user_ID')
        ->join('mst_genders','mst_genders.id','users.gender_ID')
        ->join('cities','cities.id','users.city_ID')
        ->join('states','states.id','cities.state_id')
        ->when(Auth::user()->role_ID === config('constant.doctor_role_ID'), function($query)
        {
            $query->where('doctors.user_ID',Auth::user()->id);
        })
        ->where('patients.isActive',1)
        ->where('users.isActive',1)
        ->groupBy('appointments.patient_ID')
        ->get([ 
            'patients.id',
            'patients.user_ID',
            'appointments.doctorTimeSlot_ID',
            'appointments.patient_ID',
            DB::raw('CONCAT_WS(" ", users.first_name, users.last_name) as patient_full_name'),
            'email','mobile','age','address','gender','mst_genders.gender','cities.name AS city','states.name AS state', 'role_ID'
        ])->toArray();
    }

    public static function deletePatient($id)
    {
        return Patients::where('id',$id)
        ->update([
            'isActive' => 0,
            'isDeleted' => 1,
            'deletedAt' => now(),
            'deletedBy' => Auth::user()->id
        ]);
    }

    public function prescriptions()
    {
        return $this->hasOne(Patients::class,'patient_ID');    
    }

    public static function getPatientNames()
    {
        return Patients::leftJoin('appointments','appointments.patient_ID','patients.id')
            ->leftJoin('doctor_time_slots','doctor_time_slots.id','appointments.doctorTimeSlot_ID')
            ->leftJoin('doctors','doctors.id','doctor_time_slots.doctor_ID')
            ->leftJoin('users','users.id','patients.user_ID')
            ->where('doctors.user_ID',Auth::user()->id)
            ->where('patients.isActive',1)
            ->where('users.isActive',1)
            ->groupBy('appointments.patient_ID')
             ->get([ 
                'patients.id',
                'patients.user_ID',
                DB::raw('CONCAT_WS(" ", users.first_name, users.last_name) as patient_full_name')
            ]);
    }

    public static function getPatientDetails($appointment_ID)
    {
        $patientDetails =   Appointments::join('patients','patients.id','appointments.patient_ID')
                            ->join('users','users.id','patients.user_ID')
                            ->where('appointments.id', $appointment_ID)
                            ->select(
                                DB::raw('CONCAT_WS(" ", users.first_name, users.last_name) as patient_full_name'),
                                'users.email','users.mobile'
                            )
                            ->get();

        return (!empty($patientDetails)) ? $patientDetails[0] : null;
    }
}