<div class="card appointment-card mb-5">
    <div class="card-header">
        <div class="text-white d-flex justify-content-between align-items-center rounded">
            <span style="color: white;"><b>{{ $appointment->appointment_no ?? "" }}</b></span>
            <input type="hidden" id="hidden_amount-{{ $appointment->id }}" value="{{ $appointment->amount ?? 0 }}">
                <span>
                    <span class="fw-bold fs-5 text-white" role="button" data-bs-toggle="modal" data-bs-target="#editAmountModal">
                        ₹  {{ $appointment->amount ?? 0 }}
                    </span>&nbsp;

                    @if(in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids')))
                        @if($status == 'confirmed')
                            <button class="btn btn-sm btn-light ms-2 amount" data-id="{{ $appointment->id }}" title="edit amount">
                                <i class="fas fa-edit"  aria-hidden="true"></i>
                            </button>
                        @endif
                    @endif
                </span>
        </div>
        
    </div>
    <div class="appointment-details">
        @if(Auth::user()->role_ID == config('constant.patients_role_ID'))
            <p style="color: black;"><b>Doctor Name :</b> {{ $appointment->doctor_full_name }}</b></p>
        @elseif(Auth::user()->role_ID == config('constant.doctor_role_ID'))
            <p style="color: black;" class="fw-bold fs-4"><b>Patient Name :</b> {{ $appointment->patient_full_name }}</p>
        @endif

        @if(Auth::user()->role_ID == config('constant.admin_role_ID'))
            <p style="color: black;"><b>Doctor Name:</b> Dr. {{ $appointment->doctor_full_name }}</p>
            <p style="color: black;"><b>Patient Name:</b> {{ $appointment->patient_full_name }}</p>
        @endif
        @if(
            Auth::user()->role_ID == config('constant.admin_role_ID') || Auth::user()->role_ID == config('constant.patients_role_ID')
        )
        <p style="color: black;"><strong>Specialty:</strong> {{ $appointment->specialtyName }}</p>
        @endif
        <p style="color: black;"><strong>Date:</strong> {{ $appointment->appointmentDate }}</p>
        <p style="color: black;"><strong>Time:</strong> {{ $appointment->time }}</p>
        <p style="color: black;"><strong>Status:</strong> 
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
                <button class="btn btn-success appointmentButoon" id="confirm_button" data-id="{{ $appointment->id }}" data-status="confirmed" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                    <i class="fas fa-check"></i>
                    Confirm Appointment
                </button>
            @endif

            @if($status == 'pending' && (Auth::user()->role_ID == config('constant.patients_role_ID') || Auth::user()->role_ID == config('constant.admin_role_ID')))
                <button style="background-color: #86a1ce;color:white" class="btn appointmentEditButton" id="edit_button" data-id="{{ $appointment->id }}" data-status="pending" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                    <i class="fas fa-edit"></i>
                    Edit Appointment
                </button>
            @endif

            @if($status === 'completed')
                <button class="btn btn-dark unComplete" id="archieve_button" data-date="{{ $appointment->appointmentDate }}" data-id="{{ $appointment->id }}" data-status="archived" data-patient_ID = "{{ $appointment->patient_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                    <i class="fas fa-box-archive"></i>
                    Archieve Appointment
                </button>

                @if(in_array(Auth::user()->role_ID, config('constant.admin_and_doctor_role_ids')))
                    @if(empty($appointment->prescriptions_ID))
                        <button class="btn btn-primary add_prescriptions" id="prescriptions" data-id="{{ $appointment->id }}" data-status="completed" data-patient_ID = "{{ $appointment->patient_ID }}" data-doctor_id = "{{ $appointment->doctor_ID }}" data-priscription_id = "{{ $appointment->prescriptions_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"
                        @if($appointment->payment_status == 'pending') disabled @endif>
                            <i class="fas fa-comment-medical"></i>
                            Add Prescriptions 
                        </button>
                    @else
                        <button class="btn btn-primary add_prescriptions" id="edit_prescriptions" data-id="{{ $appointment->id }}" data-status="completed" data-patient_ID = "{{ $appointment->patient_ID }}" data-doctor_id = "{{ $appointment->doctor_ID }}" data-priscription_id = "{{ $appointment->prescriptions_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                            <i class="fas fa-comment-medical"></i>
                            Edit Prescriptions 
                        </button>
                    @endif
                @else
                    @if(!empty($appointment->prescriptions_ID))
                        <a href="{{ route('appointments.prescription-download',['id' => $appointment->prescriptions_ID]) }}">
                            <button name="prescriptions" class="btn btn-primary btn border text-white download_prescriptions" data-placement="bottom" title="Download Prescriptions Summary"  data-bs-toggle="modal" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                                <i class="fas fa-download"></i> 
                                Download Prescriptions
                            </button>
                        </a>
                    @endif
                @endif
            @endif
            
            @if($status != 'canceled' && $status != 'completed')
                <button class="btn btn-dark rescheduleAppointment" id="reshedule_button" data-date="{{ $appointment->appointmentDate }}" data-id="{{ $appointment->id }}" data-status="reshedule" data-patient_ID = "{{ $appointment->patient_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                    <i class="fas fa-calendar"></i>
                    Reschedule Appointment
                </button>
            @endif

            @if($status == 'confirmed' && (in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids'))))
                <button class="btn btn-info appointmentButoon" id="complete_button" data-id="{{ $appointment->id }}" data-status="completed" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                    <i class="fas fa-check"></i>
                    Mark As Complete
                </button>
            @endif

            @if($status !== 'canceled' && $status !== 'completed')
                <button class="btn btn-danger appointmentButoon" id="cancel_button" data-id="{{ $appointment->id }}" data-status="cancelled" data-date="{{ $appointment->appointmentDate }}" data-patient_ID = "{{ $appointment->patient_ID }}" data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}"  data-timeslot_id = "{{ $appointment->doctorTimeSlot_ID }}">
                    <i class="fa-sharp fa-solid fa-rectangle-xmark"></i>
                    Cancel Appointment
                </button>
            @endif

            @if(in_array(Auth::user()->role_ID,config('constant.admin_and_doctor_role_ids')))
                <a href = "{{ route('admin.view-patient-history',['patients' => $appointment->patient_ID]) }}">
                    <button class="btn btn-info view" id="view_details" data-date="{{ $appointment->appointmentDate }}" data-id="{{ $appointment->id }}" data-status="archived" data-patient_ID = "{{ $appointment->patient_ID }}"><i class="fas fa-eye"></i>
                    View Patient Details</button>
                </a>
            @endif
        </div>
    </div>
</div>
