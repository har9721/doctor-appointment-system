<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentDetails extends Model
{
    use HasFactory;

    protected $table = 'payment_details';

    protected $fillable = ['appointment_ID','res_payment_id','order_id','transaction_id','method','currency','email','phone','amount','status','json_response','createdBy', 'payment_type'];

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

    public static function getRevenueDetails($start, $end)
    {
        return PaymentDetails::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->select(
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('DATE_FORMAT(created_at, "%M-%Y") as month_year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                'created_at'
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }
}
