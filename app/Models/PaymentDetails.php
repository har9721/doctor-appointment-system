<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    use HasFactory;

    protected $table = 'payment_details';

    protected $fillable = ['appointment_ID','res_payment_id','order_id','transaction_id','method','currency','email','phone','amount','status','json_response','createdBy'];

    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }

    public function amount()
    {
        return number_format($this->amount / 100, 2);
    }

    public function appointments()
    {
        return $this->belongsTo(Appointments::class,'appointment_ID');
    }
}
