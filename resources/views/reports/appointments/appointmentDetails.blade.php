@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">{{ucfirst($status)}} Appointment Details Report</h4>
            <div class="text-right">
                <a href="{{ route('appointments.reports.doctorPerformanceReport') }}">
                    <button type="button" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-circle-left"></i>
                        Back
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="appointmentDetailsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Sr.No</th>
                            <th>Patients Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
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
    let getAppointmentDetails = "{{ route('appointments.reports.fetchAppointmentDetails') }}";
    let loadDoctorPerformanceTable = false;
    let loadAppointmentDetailsTable = true;
    let doctor_id = @json($id);
    let statusofAppointment =  @json($status);
    let reportKey = @json($reportKey);
    let doctorPerformance = null;
</script>

<script src="{{ asset('js/Reports/doctorPerformance.js') }}"></script>
@endpush