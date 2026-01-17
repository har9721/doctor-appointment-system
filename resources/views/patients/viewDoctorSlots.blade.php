@extends('layouts.app')
<style>
    .fc-daygrid-day-bottom{
        padding-bottom: 20px;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Check Availability & Book Appointment</h4>
            <div class="text-right">
            </div>
        </div>
        <div class="card-body"> 
            <div class="mb-4 row col-md-4">
                <input type="hidden" id="patient_id" value="{{ $patient_id }}" />
                <select id="doctorSelect" class="form-control" >
                    <option value="">Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->user->doctor_name }} - {{ $doctor->specialty->specialtyName }}</option>
                    @endforeach
                </select>
                <span style="color: red;">Note: Please select doctor to view his availability.</span>
            </div>

            <div id="calendar"></div>
        </div>
    </div>
</div>

<x-appointment-reason />

@endsection
@push('scripts')
<script>
    let getDoctorTimeSlot = '{{ route("patients.doctor-time-slot") }}';
    let bookingUrl = "{{ route('patients.book-appointment') }}";
    let bookingWithPaymentGateway = "{{ route('payments.advance-payment') }}";
    let razorpayKey = "{{ config('services.razorpay.RAZORPAY_KEY_ID') }}";
    let successUrl = "{{ url('payment/success') }}";
    let successRoute = "{{ route('payment.success') }}";
    let successPage = "{{ route('payments.success-page') }}";
    let failedUrl = "{{ url('payment/fail') }}";
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('js/Patient/viewDoctorTimeSlot.js') }}"></script>
@endpush