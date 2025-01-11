@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header text-center">
            <h2>Payment Successful</h2>
        </div>
        <div class="card-body">
            <h4 class="text-success text-center">Thank you for your payment!</h4>
            <p class="mb-2">Your payment details are as follows:</p>

            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Order ID:</strong> {{ $payment->order_id }}</li>
                <li class="list-group-item"><strong>Payment ID:</strong> {{ $payment->res_payment_id }}</li>
                <li class="list-group-item"><strong>Amount Paid:</strong> ₹{{ number_format($payment->amount / 100, 2) }}</li>
                <li class="list-group-item"><strong>Currency:</strong> {{ $payment->currency }}</li>
                <li class="list-group-item"><strong>Status:</strong> {{ $payment->status }}</li>
            </ul>
            <a href="{{ route('appointments.completed-list') }}" class="btn btn-primary">Back to Appointments</a>
        </div>
    </div>
</div>
@endsection