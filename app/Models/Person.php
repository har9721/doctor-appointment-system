<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'person';

    public $timestamps = false;

    protected $appends = ['full_name'];

    protected $fillable = ['first_name','last_name','email','age','mobile','gender_ID','address','city_ID','created_at'];

    public static function addPerson($data)
    {
        $insertPerson = Person::create([
            'first_name' => ucfirst(trim($data['first_name'])),
            'last_name' => ucfirst(trim($data['last_name'])),
            'email' => trim($data['email']),
            'age' => $data['age'],
            'mobile' => $data['mobile'],
            'gender_ID' => $data['gender'],
            'address' => isset($data['address']) ? $data['address'] : null,
            'city_ID' => $data['city'],
            'created_at' => now(),
        ]);

        if($insertPerson)
        {
            $data['person_ID'] = $insertPerson->id;

            if($data['isPatients'] == 0)
                return Doctor::addDoctors($data);
            else
                return Patients::addPatients($data);
        }else{
            return 0;
        }
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class,'person_ID')->where('isActive',1)->select('id','person_ID','specialty_ID','licenseNumber','isActive');    
    }

    public function city()
    {
        return $this->belongsTo(city::class,'city_ID')->select('id','name');    
    }

    public function gender()
    {
        return $this->belongsTo(MstGender::class,'gender_ID')->where('isActive',1)->select('id','gender','isActive');    
    }

    public function fullName() : Attribute
    {
        return new Attribute(
            get: fn() => $this->first_name .' ' .$this->last_name
        );    
    }

    public function patients()
    {
        return $this->hasOne(Patients::class,'person_ID');
    }
}
