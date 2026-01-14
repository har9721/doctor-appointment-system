@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Doctors</h4>
            <div class="text-right">
                <a href="{{ route('admin.add-doctor') }}">
                    <button type="button" class="btn btn-success mr-2" id="addDoctorBtn">
                        Add Doctor
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="doctorList" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center" style="color:black">
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>City</th>
                            <th>Speciality</th>
                            <th>License Number</th>
                            <th>Experience</th>
                            <th>TimeSlot</th>
                            <th>Consultation Fees</th>
                            <th>FollowUp Fees</th>
                            <th>Payment Mode</th>
                            <th>Advance Fees</th>
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
    let getDoctorList = "{{ route('admin.doctor-list') }}";
    let deleteDoctor = "{{ route('admin.deleteDoctor') }}";
    let sendMail = "{{ route('admin.send-time-slot-mail',':id') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/Doctor/doctor.js') }}"></script>
@endpush