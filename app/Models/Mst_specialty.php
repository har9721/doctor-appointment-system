<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mst_specialty extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'mst_specialties';

    protected $fillable = ['specialtyName','created_at'];

    public static function addSpecialty($data)
    {
        if(empty($data['hidden_id']))
        {
            return Mst_specialty::create([
                'specialtyName' => ucfirst(trim($data['name'])),
                'created_at' => now(),
                'createdBy' => Auth::user()->id
            ]);
        }else{
            return Mst_specialty::where('id',$data['hidden_id'])
            ->update([
                'specialtyName' => ucfirst(trim($data['name'])),
                'updated_at' => now(),
                'updatedBy' => Auth::user()->id
            ]);
        }
    }

    public static function getAllSpecialty() 
    {
        return Mst_specialty::where(['isActive' => 1, 'isDeleted' => 0])->latest('id','desc')->get(['id','specialtyName'])->toArray();
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class,'specialty_ID');
    }

    public static function deleteSpecialty($id)
    {
        return Mst_specialty::where('id',$id)->update([
            'isActive' => 0,
            'isDeleted' => 1,
            'deletedBy' => Auth::user()->id,
            'deletedAt' => now(),
        ]);
    }
}
