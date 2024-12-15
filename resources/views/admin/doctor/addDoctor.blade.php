@extends('layouts.app')
@push('css')
    <style>
        .errorMessages{
            color: red;
        }
        .select2-container .select2-selection--single
        {
            height: 36px !important;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Doctor Registration Form</h4>
            <div class="text-right">
                <a href="{{ route('admin.doctor') }}">
                    <button type="button" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-circle-left"></i>
                        Back
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form id="doctorForm" enctype="multipart/form-data">
                <input type="hidden" name="isPatients" value="0">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="first_name"><b>First Name<span style="color: red;">*</span> : </b></label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="enter first name...">
                    </div>

                    <div class="col-md-3">
                        <label for="last_name"><b>Last Name<span style="color: red;">*</span> :</b></label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="enter last name...">
                    </div>

                    <div class="col-md-3">
                        <label for="email"><b>Email<span style="color: red;">*</span> :</b></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="enter email...">
                    </div>

                    <div class="col-md-3">
                        <label for="mobile"><b>Mobile<span style="color: red;">*</span> :</b></label>
                        <input type="number" id="mobile" name="mobile" class="form-control" placeholder="enter mobile...">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="gender"><b>Gender<span style="color: red;">*</span> : </b></label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">select gender</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="age"><b>Age<span style="color:red">*</span></b> :</label>
                        <input type="text" class="form-control" name="age" id="age" value="" placeholder="enter age...">
                    </div>

                    <div class="col-md-3">
                        <label for="state"><b>State</b><span style="color:red">*</span> :</label>
                        <select id="state" class="form-control" name="state">
                            <!-- <option value="">Select State</option> -->
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="city"><b>City</b><span style="color:red">*</span> :</label>
                        <select id="city" class="form-control" name="city">
                            <option value="">Select City</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="speciality"><b>Speciality<span style="color: red;">*</span> :</b></label>
                        <select class="form-control" id="speciality" name="speciality">
                            <option value="">select speciality</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="licenseNumber"><b>Medical License Number <span style="color: red;">*</span> :</b></label>
                        <input type="text" name="licenseNumber" class="form-control" id="licenseNumber" value="" placeholder="enter medical license number...">
                    </div>

                    <div class="col-md-2">
                        <label for="experience"><b>Experience<span style="color: red;">*</span> :</b></label>
                        <input type="number" class="form-control" name="experience" id="experience" placeholder="enter experience">
                    </div>

                    <div class="col-md-4">
                        <label for="image"><b>Profile Image <span style="color: red;">*</span> :</b></label>
                        <input type="file" name="profile_image" accept="image/*" class="form-control" id="image" value="" >
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-success" id="submitForm"><i class="fas fa-save pr-1"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let saveDoctorDetails = "{{ route('admin.doctorRegister') }}";
    let getGender = "{{ route('get-gender') }}";
    let getCity = "{{ route('get-city') }}";
    let getStates = "{{ route('get-state') }}";
    let getSpecialty = "{{ route('admin.specialtyList') }}"
    let getDoctorList = "{{ route('admin.doctor') }}"
</script>
<script src="{{ asset('js/Doctor/doctorRegister.js') }}"></script>
@endpush