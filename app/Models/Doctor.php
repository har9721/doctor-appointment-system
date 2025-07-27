<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return $this->belongsTo(User::class,'user_ID')->where('isActive',1)->select('id','first_name','last_name','email','mobile','age','city_ID','gender_ID',DB::raw('CONCAT_WS(" ", first_name, last_name) as doctor_name'));    
    }

    public function specialty()
    {
        return $this->belongsTo(Mst_specialty::class,'specialty_ID')->where('isActive',1)->select('id','specialtyName','isActive');    
    }

    public static function getLoginDoctorID()
    {
        return Doctor::where('user_id',Auth::user()->id)->first('id');
    }

    public function timeSlot()
    {
        return $this->hasMany(DoctorTimeSlots::class,'doctor_ID')->where('isDeleted',0)->where('status','available')->select('id','availableDate','start_time','end_time','doctor_ID','isBooked');
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

    public function prescriptions()
    {
        return $this->hasOne(Prescriptions::class,'doctor_ID');    
    }

    public function appointments(): HasManyThrough
    {
        return $this->hasManyThrough(Appointments::class, DoctorTimeSlots::class, 'doctor_ID', 'doctorTimeSlot_ID')->select('appointments.id','appointments.doctorTimeSlot_ID','appointments.patient_ID','appointments.appointmentDate','appointments.isBooked','appointments.created_at','appointments.appointment_reminder_time','appointments.isReminderSent');
    }

    public function getDoctorPerformance($inputs)
    {
        return Doctor::select( 
                'id',
                'user_ID',
                'specialty_ID'
            )
            ->when(auth()->user()->role->roleName === 'Doctor', function($query){
                $doctor_ID = Doctor::getLoginDoctorID();

                return $query->where('id',$doctor_ID->id);
            })
            ->when(!empty($inputs['id']), function($query) use($inputs){
                return $query->whereIn('id', $inputs['id']);
            })
            ->withCount([
                'appointments as completed_count' => function ($query1) use($inputs){
                    $query1
                        ->when(
                            !empty($inputs['from_date']) && !empty($inputs['to_date']),
                            function($query) use($inputs)
                            {
                                $startDate = date('Y-m-d', strtotime($inputs['from_date']));
                                $toDate = date('Y-m-d', strtotime($inputs['to_date']));

                                return $query->whereBetween('appointmentDate',[$startDate, $toDate]);
                            }
                        );

                    $query1->where('appointments.status', 'completed')->where('isActive',1);
                },
                'appointments as cancelled_count' => function ($query1) use($inputs){
                    $query1
                        ->when(
                            !empty($inputs['from_date']) && !empty($inputs['to_date']),
                            function($query) use($inputs)
                            {
                                $startDate = date('Y-m-d', strtotime($inputs['from_date']));
                                $toDate = date('Y-m-d', strtotime($inputs['to_date']));

                                return $query->whereBetween('appointmentDate',[$startDate, $toDate]);
                            }
                        );

                    $query1->where('appointments.status', 'cancelled')->where('isActive',1);
                },
            ])
            ->with(['user'])
            ->where('isActive',1)
            // ->groupBy('doctors.id') // comment this because withCount() function internally apply grouping
            ->get();

            //using joins
            // return Doctor::leftJoin('doctor_time_slots', 'doctor_time_slots.doctor_ID','doctors.id')
            //         ->leftJoin('appointments', 'appointments.doctorTimeSlot_ID', 'doctor_time_slots.id')
            //         ->leftJoin('users as d', 'd.id', 'doctors.user_ID')
            //          ->where('doctors.isActive', 1)
            //         ->where(function ($query) {
            //             $query->whereNull('doctor_time_slots.isDeleted')
            //                 ->orWhere('doctor_time_slots.isDeleted', 0);
            //         })
            //         ->where(function ($query) {
            //             $query->whereNull('appointments.isActive')
            //                 ->orWhere('appointments.isActive', 1);
            //         })
            //         ->select(
            //             'doctors.id',
            //             'doctors.user_ID',
            //             DB::raw('SUM(CASE WHEN appointments.status = "Completed" THEN 1 ELSE 0 END) as completed_count'),
            //             DB::raw('SUM(CASE WHEN appointments.status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_count'),
            //             DB::raw('CONCAT_WS(" ", d.first_name, d.last_name) as doctor_full_name'),
            //         )
            //         ->groupBy('doctors.id')
            //         ->get()->toArray();
    }

    public static function getDoctorList()
    {
        return  Doctor::with(['user'])
                ->where('isActive', 1)
                ->when(auth()->user()->role->roleName == "Doctor", function ($query){
                    $doctor_ID = Doctor::getLoginDoctorID();

                    return $query->where('id', $doctor_ID->id);
                })
                ->get(['id','user_ID']);
    }
}
