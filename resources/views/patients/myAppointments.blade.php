@extends('layouts.app')
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 30px;
        }
        .appointment-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .appointment-card:hover {
            transform: translateY(-5px);
        }
        .appointment-card .card-header {
            background-color: #007bff;
            color: white;
        }
        .appointment-details {
            padding: 20px;
        }
        .appointment-status {
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 30px;
            text-align: center;
        }
        .status-confirmed {
            background-color: #28a745;
            color: white;
        }
        .status-pending {
            background-color: #ffc107;
            color: white;
        }
        .status-canceled {
            background-color: #dc3545;
            color: white;
            /* background: #1de9b6;         */
        }
        .status-completed {
            background-color: #1de9b6;
            color: black;
        }
        .time-slot {
            padding: 5px 8px;
            margin-bottom: 10px;
            border-radius: 30px;
            background-color: #f7f9fc;
            border: 1px solid #007bff;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #007bff;
        }

        .time-slot:hover {
            background-color: #007bff;
            color: white;
        }
        .time-slots-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .selected {
            background-color: #007bff !important;
            color: white !important;
        }
    </style>
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">{{ $heading }}</h4>
            <div class="text-right">
                <a href="{{ route('patients.appointment-booking') }}"><button class="btn btn-info">Book Appointment</button></a>
            </div>
        </div>
        <div class="card-body">
            <form id="dateFilterForm" method="GET"  action="{{route('appointments.my-appointments')}}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="date">From Date : <span style="color: red;">*</span></label>
                        <input type="text" id="from_date" name="from_date" class="datetimepicker form-control" value="<?php echo date('01-m-Y') ?>" onkeydown="return false;">
                    </div>
                    <div class="col-md-3">
                        <label for="date">To Date : <span style="color: red;">*</span></label>
                        <input type="text" id="to_date" name="to_date" class="datetimepicker form-control" value="<?php echo $to_date ?>" onkeydown="return false;">
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-success form-group mt-2" id="search">Search</button>
                    </div>
                </div>
            </form>

            <ul class="nav nav-pills nav-fill" id="appointmentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link show active" data-toggle="tab" id="pending_tab" aria-current="page" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="confirmed_tab" data-toggle="tab" href="#confirmed" role="tab" aria-controls="confirmed" aria-selected="false">Confirmed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="cancelled_tab" data-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">Cancelled</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="completed_tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed</a>
                </li>
            </ul>


            <div class="tab-content" id="appointmentTabsContent">
                <!-- pending Tab -->
                <div class="tab-pane fade show active mt-5" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    @if($myPendingAppointments->isEmpty())
                        <p class="mt-4" style="text-align: center;">No pending appointments found for the selected date range.</p>
                    @else
                        @foreach($myPendingAppointments as $appointment)
                            <x-appointment-card :appointment="$appointment" status="pending" />
                        @endforeach
                    @endif
                </div>

                <!-- confirmed tab -->
                <div class="tab-pane fade mt-5" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                    @if($myConfirmedAppointments->isEmpty())
                        <p class="mt-4" style="text-align: center;">No confirmed appointments found for the selected date range.</p>
                    @else
                        @foreach($myConfirmedAppointments as $appointment)
                            <x-appointment-card :appointment="$appointment" status="confirmed" />
                        @endforeach
                    @endif
                </div>

                <!-- Canceled Tab -->
                <div class="tab-pane fade mt-5" id="canceled" role="tabpanel" aria-labelledby="cancelled_tab">
                    @if($myCancelledAppointments->isEmpty())
                        <p class="mt-4" style="text-align: center;">No cancelled appointments found for the selected date range.</p>
                    @else
                        @foreach($myCancelledAppointments as $appointment)
                            <x-appointment-card :appointment="$appointment" status="canceled" />
                        @endforeach
                    @endif
                </div>

                <!-- Completed Tab -->
                <div class="tab-pane fade mt-5" id="completed" role="tabpanel" aria-labelledby="completed_tab">
                    @if($completedAppointments->isEmpty())
                        <p class="mt-4" style="text-align: center;">No completed appointments found for the selected date range.</p>
                    @else
                        @foreach($completedAppointments as $appointment)
                            <x-appointment-card :appointment="$appointment" status="completed" />
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<!-- --------------------reschedule modal-------------- -->
<x-reschedule-modal-component/>

<!-- ----------include add amount modal---------------- -->
<x-addAmount />

@endsection
@push('scripts')
<script>
    let mark_appointments = "{{ route('appointments.mark-appoitment') }}";
    let reschedule_appointment = "{{ route('appointments.reschedule-appoitment') }}";
    let fetch_appointments_details = "{{ route('appointments.get-appointments-details') }}";
    let fetch_doctor_time_slot = "{{ route('appointments.fetch-time-slot') }}";
    let saveAmount = "{{ route('appointments.save-amount') }}";
</script>
<script src="{{ asset('js/Appointments/appointments.js') }}"></script> 
@endpush