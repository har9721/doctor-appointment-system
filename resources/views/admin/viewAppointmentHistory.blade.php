@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Appointment History List</h4>
        </div>
        <div class="card-body">
            <form id="dateFilterForm" method="GET"  action="">
                <div class="row">
                    <input type="hidden" name="role_id" id="role_id" value="{{ config('constant.admin_role_ID') }}">
                    <div class="col-md-3">
                        <label for="date">From Date : <span style="color: red;">*</span></label>
                        <input type="text" id="from_date" name="from_date" class="datetimepicker form-control" value="<?php echo date('01-m-Y') ?>" onkeydown="return false;">
                    </div>
                    <div class="col-md-3">
                        <label for="date">To Date : <span style="color: red;">*</span></label>
                        <input type="text" id="to_date" name="to_date" class="datetimepicker form-control" value="<?php echo date('d-m-Y',strtotime(date('t-m-Y'))) ?>" onkeydown="return false;">
                    </div>
                    <div class="col-md-3">
                        <label for="date">Status <span style="color: red;">*</span></label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="payment_pending">Payment Pending</option>
                        </select>
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
                <table class="table table-bordered" id="appointmentHistory" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Doctor Name</th>
                            <th>Patient Name</th>
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
@endsection
@push('scripts')
<script>
    let getAppointmentList = "{{route('appointments.get-appointment-completed-list')}}";
    let sendMail = "{{ route('payments.send-payment-mail') }}";
    let roleName = "{{ Auth::user()->role->roleName }}";
</script>
    <script src="{{ asset('js/Appointments/appointmentHistory.js') }}"></script>
@endpush