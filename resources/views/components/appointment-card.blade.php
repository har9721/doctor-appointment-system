<div class="card appointment-card mb-5">
    <div class="card-header">
        @if(in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids')))
            <div class="text-white d-flex justify-content-between align-items-center rounded">
                @if(Auth::user()->role_ID == config('constant.admin_role_ID'))
                    <span style="color: black;"><b>Dr. {{ $appointment->doctor_full_name }}</b></span>
                @else 
                    <span class="fw-bold fs-4">Patient : {{ $appointment->patient_full_name }}</span>
                @endif
                <span>
                    <span class="fw-bold fs-5 text-white" role="button" data-bs-toggle="modal" data-bs-target="#editAmountModal">
                        ₹  {{ $appointment->amount ?? 0 }}
                    </span>&nbsp;

                    @if($status == 'confirmed')
                        <button class="btn btn-sm btn-light ms-2 amount" data-id="{{ $appointment->id }}" title="edit amount">
                            <i class="fas fa-edit"  aria-hidden="true"></i>
                        </button>
                    @endif
                </span>
            </div>
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
            @elseif($status == 'canceled')
                <span class="appointment-status status-canceled">Canceled</span>
            @else
                <span class="appointment-status status-completed">Completed</span>
            @endif
        </p>
        <div class="text-right">
            @if($status == 'pending' && (in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids'))))
                <button class="btn btn-success appointmentButoon" id="confirm_button" data-id="{{ $appointment->id }}" data-status="confirmed" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}">Confirm Appointment</button>
            @endif

            @if($status === 'completed')
                <button class="btn btn-dark unComplete" id="archieve_button" data-date="{{ $appointment->appointmentDate }}" data-id="{{ $appointment->id }}" data-status="archived" data-patient_ID = "{{ $appointment->patient_ID }}">Archieve Appointment</button>
            @endif
            
            @if($status != 'canceled' && $status != 'completed')
                <button class="btn btn-dark rescheduleAppointment" id="reshedule_button" data-date="{{ $appointment->appointmentDate }}" data-id="{{ $appointment->id }}" data-status="reshedule" data-patient_ID = "{{ $appointment->patient_ID }}">Reschedule Appointment</button>
            @endif

            @if($status == 'confirmed' && (in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids'))))
                <button class="btn btn-info appointmentButoon" id="complete_button" data-id="{{ $appointment->id }}" data-status="completed" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}">Mark As Complete</button>

                <button class="btn btn-danger appointmentButoon" id="cancel_button" data-id="{{ $appointment->id }}" data-status="cancelled" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}">Cancel Appointment</button>
            @endif
        </div>
    </div>
</div>
