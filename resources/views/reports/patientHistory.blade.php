@extends('layouts.app')

<style>
    .select2-container .select2-selection--single
    {
        height: 36px !important;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Patient Visit History Report</h4>
        </div>
        <div class="card-body">
            <input type="hidden" id="role" value="{{ auth()->user()->role->roleName }}">
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

                    @if(auth()->user()->role->roleName != 'Patients')
                        <div class="col-md-3">
                            <label for="date" class="font-weight-bold">Patients Name : </label>
                            <select class="form-control" id="patient_name_list" name="patient_name">
                                <option value="">Select Patient</option>
                            </select>
                        </div>
                    @endif

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
                <table class="table table-bordered" id="patientHistoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Apointment No.</th>
                            @if(auth()->user()->role_ID == 3)
                                <th>Doctor Name</th>
                            @else
                                <th>Patient Name</th>
                            @endif
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Reason</th>
                            <th>Diagnosis</th>
                            <th>Status</th>
                            <th>Prescriptions</th>
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

<x-view-prescription-summary />

@endsection
@push('scripts')

<script type="text/javascript">
    let getPatientsHistory = "{{ route('patients.reports.fetchHistory') }}";
    let fetchPrescriptionsDetails = "{{ route('appointments.prescription.get') }}";
    let getPatientList = "{{ route('get-patient-list') }}";
</script>

<script src="{{ asset('js/Reports/patientsHistory.js') }}"></script>
@endpush