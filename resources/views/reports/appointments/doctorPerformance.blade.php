@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Doctor Performance Report</h4>
        </div>
        <div class="card-body">
            <form id="dateFilterForm" method="GET"  action="">
                <div class="row">
                    <div class="col-md-3">
                        <label for="date" class="font-weight-bold">From Date : <span style="color: red;">*</span></label>
                        <input type="text" id="from_date" name="from_date" class="datetimepicker form-control" value="<?php echo date('01-m-Y') ?>" onkeydown="return false;">
                    </div>

                    <div class="col-md-3">
                        <label for="date" class="font-weight-bold">To Date : <span style="color: red;">*</span></label>
                        <input type="text" id="to_date" name="to_date" class="datetimepicker form-control" value="<?php echo date('d-m-Y',strtotime(date('t-m-Y'))) ?>" onkeydown="return false;">
                    </div>

                    @if(auth()->user()->role->roleName != 'Doctor')
                        <div class="col-md-3">
                            <label for="date" class="font-weight-bold">Doctor Name : </label>
                            <select class="form-control js-example-basic-multiple" id="doctor_name_list" multiple="multiple" name="doctor_name[]" aria-placeholder="select doctor">
                                <option value="" selected>Select Doctor</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-md-3 mt-4">
                        <button type="button" onclick="reload_table()" class="btn btn-success form-group mt-2" id="search">Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="doctorPerformanceReport" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Doctor Name</th>
                            <th>Pending Appointment</th>
                            <th>Completed Appointment</th>
                            <th>Cancelled Appointment</th>
                            <th>Revenue Generated</th>
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

<script type="text/javascript">
    let doctorPerformance = "{{ route('appointments.reports.fetch-doctor-performance') }}";
    let loadDoctorPerformanceTable = true;
    let loadAppointmentDetailsTable = false;
    let getAppointmentDetails = null;
    let getDoctorList = "{{ route('get-doctor-list') }}"
</script>

<script src="{{ asset('js/Reports/doctorPerformance.js') }}"></script>
@endpush