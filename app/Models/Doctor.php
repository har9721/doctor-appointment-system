<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    public $timestamps = false;

    protected $fillable = ['person_ID','specialty_ID','licenseNumber','created_at'];

    public static function addDoctors($data)
    {   
        $insertDoctor = Doctor::create([
            'person_ID' => $data['person_ID'],
            'specialty_ID' => $data['speacility'],
            'licenseNumber' => trim($data['licenseNumber']),
            'created_at' => now(),
        ]);

        if($insertDoctor)
        {
            $data['role'] = 2;
            return User::addUser($data);
        }
    }

    public function person()
    {
        return $this->belongsTo(Person::class,'person_ID')->where('isActive',1)->select('id','first_name','last_name','email','mobile','age','city_ID','gender_ID');    
    }

    public function specialty()
    {
        return $this->belongsTo(Mst_specialty::class,'specialty_ID')->where('isActive',1)->select('id','specialtyName','isActive');    
    }

    public static function getLoginDoctorID()
    {
        return Doctor::join('users','users.person_ID','doctors.person_ID')->where('users.id',Auth::user()->id)->first('doctors.id');
    }

    public function timeSlot()
    {
        return $this->hasMany(DoctorTimeSlots::class,'doctor_ID')->where('isDeleted',0)->select('id','availableDate','start_time','end_time','doctor_ID');
    }
}
