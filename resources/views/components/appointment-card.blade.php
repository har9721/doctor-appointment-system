<div class="card appointment-card mb-5">
    <div class="card-header">
        @if(Auth::user()->role_ID == config('constant.doctor_role_ID'))
            <h5 class="mb-0">Patient : {{ $appointment->patient_full_name }}</h5>
        @else
            <h5 class="mb-0">Dr. {{ $appointment->doctor_full_name }}</h5>
        @endif
    </div>
    <div class="appointment-details">
        @if(Auth::user()->role_ID == config('constant.admin_role_ID'))
            <p style="color: black;"><b>Patient Name:</b> {{ $appointment->patient_full_name }}</p>
        @endif
        <p><strong>Specialty:</strong> {{ $appointment->specialtyName }}</p>
        <p><strong>Date:</strong> {{ $appointment->appointmentDate }}</p>
        <p><strong>Time:</strong> {{ $appointment->time }}</p>
        <p><strong>Status:</strong> 
            @if($status == 'confirmed')
                <span class="appointment-status status-confirmed">Confirmed</span>
            @elseif($status == 'pending')
                <span class="appointment-status status-pending">Pending</span>
            @else
                <span class="appointment-status status-canceled">Canceled</span>
            @endif
        </p>
        <div class="text-right">
            @if($status == 'pending')
                <button class="btn btn-success appointmentButoon" id="confirm_button" data-id="{{ $appointment->id }}" data-status="confirm" data-date="{{ $appointment->appointmentDate }}">Confirm Appointment</button>
            @endif
            @if($status == 'confirmed')
                <button class="btn btn-danger appointmentButoon" id="cancel_button" data-id="{{ $appointment->id }}" data-status="cancel" data-date="{{ $appointment->appointmentDate }}">Cancel Appointment</button>
            @endif

            @if($status != 'canceled')
                <button class="btn btn-dark rescheduleAppointment" id="reshedule_button" data-date="{{ $appointment->appointmentDate }}" data-id="{{ $appointment->id }}" data-status="cancel">Reschedule Appointment</button>
            @endif
        </div>
    </div>
</div>
