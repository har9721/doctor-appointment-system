<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    protected $appends = ['full_name'];

    protected $fillable = ['first_name','last_name','age','gender_ID','address','city_ID','email','password','mobile','role_ID','created_at',];

    const DELETED_AT = 'deletedAt';

    public static function addUser($data)
    {
        $data['role'] = ($data['isPatients'] == 1) ? config('constant.patients_role_ID') : config('constant.doctor_role_ID');

        $insertUser = User::create([
            'first_name' => ucfirst(trim($data['first_name'])),
            'last_name' => ucfirst(trim($data['last_name'])),
            'email' => trim($data['email']),
            'password' => Hash::make('12345678'),
            'age' => trim($data['age']),
            'mobile' => trim($data['mobile']),
            'role_ID' => $data['role'],
            'gender_ID' => $data['gender'],
            'address' => isset($data['address']) ? trim($data['address']) : null,
            'city_ID' => $data['city'],
            'created_at' => now(),
        ]);

        if(!empty($insertUser))
        {
            $data['user_ID'] = $insertUser->id;

            if($data['isPatients'] == 0)
                return Doctor::addDoctors($data);
            else
                return Patients::addPatients($data);
        }else{
            return 0;
        }
    }

    public function city()
    {
        return $this->belongsTo(city::class,'city_ID')->select('id','name','state_id');    
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

    public static function updateUserInfo($data)
    {
        $user = User::findOrFail($data['user_ID']);
        $data['updatedBy'] = Auth::user()->id;

        // Trim all the input data
        // $data = array_map('trim', $data);

        $user->fill($data);

        $user->when(
            Auth::user()->role->roleName === 'Admin', function ($user) use($data)
            {
                $user->email = $data['email'];
            },
            function ($user)
            {
                if(Auth::user()->role->roleName === 'Doctor')
                {
                    unset($user->email);
                } 
            }
        );

        $updateUser = $user->save();

        // $updateUser = User::where('id',$data['user_ID'])
        // ->update([
        //     'first_name' => ucfirst(trim($data['first_name'])),
        //     'last_name' => ucfirst(trim($data['last_name'])),
        //     'email' => trim($data['email']),
        //     'password' => Hash::make('12345678'),
        //     'age' => trim($data['age']),
        //     'mobile' => trim($data['mobile']),
        //     'gender_ID' => $data['gender'],
        //     'address' => isset($data['address']) ? trim($data['address']) : null,
        //     'city_ID' => $data['city'],
        //     'updated_at' => now(),
        //     'updatedBy' => Auth::user()->id
        // ]);

        return (Route::currentRouteName() === 'admin.upateUserDetails') ? 1 : (
            ($updateUser) ? Doctor::updateDoctorInfo($data) : 0
        );
    }

    public static function deleteUser($data)
    {
        $deleteUser = User::where('id',$data['user_id'])->update([
            'isActive' => 0,
            'isDeleted' => 1,
            'deletedAt' => now(),
            'deletedBy' => Auth::user()->id
        ]);

        if($deleteUser)
        {
            return Doctor::deleteDoctor($data['id']);
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class,'role_ID')->where('isActive',1)->select('id','roleName');
    }
}
