<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DoctorTimeSlots extends Model
{
    use HasFactory;

    protected $table = 'doctor_time_slots';

    public $timestamps = false;

    CONST DELETED_AT = 'deletedAt';

    protected $appends = ['start','end','title'];

    protected $fillable = ['doctor_ID', 'availableDate' ,'start_time','end_time','created_at'];

    public static function addDoctorTimeSlot($data)
    {
        if($data['isEdit'] == 0)
        {
            $addOrEditTimeSlot = DoctorTimeSlots::create([
                'doctor_ID' => $data['doctor_ID'],
                'availableDate' => date('Y-m-d',strtotime($data['date'])),
                'start_time' => $data['startTime'],
                'end_time' => $data['endTime'],
                'created_at' => now(),
                'createdBy' => Auth::user()->id
            ]);
        }else{
            $addOrEditTimeSlot = DoctorTimeSlots::where('id', $data['hidden_timeslot_id'])->update([
                'start_time' => $data['startTime'],
                'end_time' => $data['endTime'],
                'updated_at' => now(),
                'updatedBy' => Auth::user()->id
            ]);
        }

        return $addOrEditTimeSlot;
    }

    public static function fetchDoctorTimeSlots()
    {
        $loginUserId = Doctor::getLoginDoctorID();

        return DoctorTimeSlots::where(['doctor_ID' => $loginUserId->id, 'isDeleted' => 0])->get(['id','start_time','end_time','availableDate'])
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
            get : fn () => ($this->isBooked == 0) ? 'Available' : 'Not Available'
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
}
