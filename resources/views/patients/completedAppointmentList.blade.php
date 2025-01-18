@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Completed Appointment List</h4>
        </div>
        <div class="card-body">
            <form id="dateFilterForm" method="GET"  action="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="date">From Date : <span style="color: red;">*</span></label>
                        <input type="text" id="from_date" name="from_date" class="datetimepicker form-control" value="<?php echo date('01-m-Y') ?>" onkeydown="return false;">
                    </div>
                    <div class="col-md-3">
                        <label for="date">To Date : <span style="color: red;">*</span></label>
                        <input type="text" id="to_date" name="to_date" class="datetimepicker form-control" value="<?php echo date('d-m-Y',strtotime(date('t-m-Y'))) ?>" onkeydown="return false;">
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="button" onclick="reload_table()" class="btn btn-success form-group mt-2" id="search">Search</button>
                    </div>
                </div>
            </form>

            <div class="row mt-5" id="loader" style="display: none;">
                <div class="col-md-12" style="text-align: center;" id="loading_image">
                Processing...<img class="ml-3" src="{{URL::to('Images/loader.gif')}}" alt="Loading..."  width="50px" height="50px"/>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="completeAppointmentList" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Doctor Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Amount (₹)</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<x-make-payment />

<x-view-payment-summary />

@endsection
@push('scripts')
<script>
    let getAppointmentList = "{{route('appointments.get-appointment-completed-list')}}";
    let savePayment = "{{ route('payments.save-payment') }}";
    let getAppointmentDetails = "{{ route('appointments.get-appointment-details') }}";
    let razorpayKey = "{{ config('services.razorpay.key') }}";
    let successRoute = "{{ route('payment.success') }}";
    let successUrl = "{{ url('payment/success') }}";
    let fetchPaymentSummary = "{{ route('payments.fetch-payment-summary') }}";
    let successPage = "{{ route('payments.success-page') }}";
</script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{ asset('js/Appointments/appointmentComplete.js') }}"></script>
    <script src="{{ asset('js/payment.js') }}"></script>
@endpush