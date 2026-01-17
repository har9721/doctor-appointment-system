<?php

namespace App\Models;

use App\Jobs\AddRecursiveTimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DoctorTimeSlots extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'doctor_time_slots';

    public $timestamps = false;

    CONST DELETED_AT = 'deletedAt';

    protected $fillable = ['doctor_ID', 'availableDate' ,'start_time','end_time','created_at'];

    protected $appends = ['start','end','title','time', 'appointmentdate'];

    public static function addDoctorTimeSlot($data)
    {
        if(empty($data['recurrence']))
        {
            if($data['isEdit'] == 0)
            {
                $isDeletedTimestamp = DoctorTimeSlots::withTrashed()->where([
                    'doctor_ID' => $data['doctor_ID'],
                    'availableDate' => date('Y-m-d',strtotime($data['date'])),
                    'start_time' => $data['startTime'],
                ])
                ->first();

                if($isDeletedTimestamp)
                {
                    $isDeletedTimestamp->isDeleted = 0;
                    $isDeletedTimestamp->deletedAt = null;

                    $addOrEditTimeSlot = $isDeletedTimestamp->save();
                }else
                {
                    $addOrEditTimeSlot = DoctorTimeSlots::create([
                        'doctor_ID' => $data['doctor_ID'],
                        'availableDate' => date('Y-m-d',strtotime($data['date'])),
                        'start_time' => $data['startTime'],
                        'end_time' => $data['endTime'],
                        'created_at' => now(),
                        'createdBy' => Auth::user()->id
                    ]);
                }
            }else{
                $addOrEditTimeSlot = DoctorTimeSlots::where('id', $data['hidden_timeslot_id'])->update([
                    'start_time' => $data['startTime'],
                    'end_time' => $data['endTime'],
                    'status' => $data['status'],
                    'updated_at' => now(),
                    'updatedBy' => Auth::user()->id
                ]);
            }
        }else{
            $data['createdBy'] = Auth::user()->id;
            $data['updatedBy'] = Auth::user()->id;

            AddRecursiveTimeSlot::dispatch($data);

            $addOrEditTimeSlot = 1;
        }

        return $addOrEditTimeSlot;
    }

    public static function fetchDoctorTimeSlots($start = null, $end = null, $doctorId = null)
    {
        return DoctorTimeSlots::with('doctor')->where([
            'doctor_ID' => $doctorId, 
            'isDeleted' => 0,
        ])
        ->when(!empty($doctorId), function($query) use ($doctorId){
            $query->where('doctor_ID', $doctorId);
        })
        ->whereBetween('availableDate', [$start, $end])
        ->get(['id','start_time','end_time','availableDate','status', 'isBooked','doctor_ID'])
        ->toArray();
    }

    public function start() : Attribute
    {
        $startTime = date('Y-m-d H:i:s', strtotime("$this->availableDate $this->start_time"));

        return new Attribute(
            get : fn () => date('Y-m-d H:i',strtotime($startTime))
        );    
    }

    public function end() : Attribute
    {
        return new Attribute(
            get : fn () => date('Y-m-d H:i',strtotime($this->availableDate.' '.$this->end_time))
        );    
    }

    public function title() : Attribute
    {
        return new Attribute(
            get : fn () => ($this->isBooked == 0) ? 'Available' : 'Booked'
        );    
    }

    public static function deleteTimeSlot($id)
    {
        return DoctorTimeSlots::where('id',$id)->update([
            'isDeleted' => 1,
            'deletedAt' => now(),
            'deletedBy' => Auth::user()->id,
        ]);    
    }

    public static function updateDoctorTimeSlot($data)
    {
        return DoctorTimeSlots::where('id',$data['id'])->update([
            'availableDate' => date('Y-m-d', strtotime($data['date'])),
            'updated_at' => now(),
            'updatedBy' => Auth::user()->id
        ]);    
    }   

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_ID')->select('id','user_ID','specialty_ID','licenseNumber','isActive','consultationFees' ,'followUpFees','payment_mode', 'advanceFees');    
    }

    public function time() : Attribute
    {
        return new Attribute(
            get : fn() =>  Carbon::parse($this->start_time)->format('h:i A').' - '.Carbon::parse($this->end_time)->format('h:i A')
            // get : fn() =>  date('H:i A',strtotime($this->start_time)).' - '.date('H:i A',strtotime($this->end_time))
        );  
    }

    public static function updateIsBookTimeSlot($data, $isActive)
    {
        return DoctorTimeSlots::where('id',$data['timeSlot'])->update([
            'isBooked' => $isActive,
            'updatedBy' => Auth::user()->id,
            'updated_at' => now()
        ]);
    }

    public function appointments()
    {
        return $this->hasOne(Appointments::class,'doctorTimeSlot_ID')->select('id','doctorTimeSlot_ID','patient_ID','appointmentDate','isBooked','created_at','appointment_reminder_time','isReminderSent');
    }

    public function getStatusAttribute($value)
    {
        return str_replace("_"," ",ucfirst($value));    
    }

    public function appointmentdate() : Attribute
    {
        return new Attribute(
            get : fn () => date('d-m-Y',strtotime($this->availableDate))
        );
    }
}
