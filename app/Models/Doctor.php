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

    const DELETED_AT = 'deletedAt';

    protected $fillable = ['fileName','experience','user_ID','specialty_ID','licenseNumber','created_at'];

    public static function addDoctors($data)
    {   
        return Doctor::create([
            'fileName' => $data['fileName'],
            'experience' => $data['experience'],
            'user_ID' => $data['user_ID'],
            'specialty_ID' => $data['speciality'],
            'licenseNumber' => trim($data['licenseNumber']),
            'created_at' => now(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_ID')->where('isActive',1)->select('id','first_name','last_name','email','mobile','age','city_ID','gender_ID');    
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

    public static function updateDoctorInfo($data)
    {
        $doctorData = array_filter([
            'fileName' => isset($data['fileName']) ? $data['fileName'] : null,
            'experience' => $data['experience'],
            'specialty_ID' => $data['speciality'],
            'licenseNumber' => trim($data['licenseNumber']),
            'updated_at' => now(),
            'updatedBy' => Auth::user()->id
        ]);

        return Doctor::where('user_ID',$data['user_ID'])->update($doctorData);
    }

    public static function deleteDoctor($id)
    {
        return Doctor::where('id',$id)
        ->update([
            'isActive' => 0,
            'isDeleted' => 1,
            'deletedAt' => now(),
            'deletedBy' => Auth::user()->id
        ]);
    }
}
