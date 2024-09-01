@extends('layouts.app')
@push('css')
    <style>
        .errorMessages{
            color: red;
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
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="first_name"><b>First Name<span style="color: red;">*</span> : </b></label>
                    <input type="text" class="form-control" id="first_name" placeholder="enter first name...">
                    <span class="errorMessages" style="display: none;" id="first_name_error"></span>
                </div>

                <div class="col-md-6">
                    <label for="last_name"><b>Last Name<span style="color: red;">*</span> :</b></label>
                    <input type="text" class="form-control" id="last_name" placeholder="enter last name...">
                    <span id="last_name_error" class="errorMessages" style="display: none;"></span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="email"><b>Email<span style="color: red;">*</span> :</b></label>
                    <input type="email" id="email" class="form-control" placeholder="enter email...">
                    <span id="email_error" class="errorMessages" style="display: none;"></span>
                </div>

                <div class="col-md-6">
                    <label for="mobile"><b>Mobile<span style="color: red;">*</span> :</b></label>
                    <input type="number" id="mobile" class="form-control" placeholder="enter mobile...">
                    <span id="mobile_error" class="errorMessages" style="display: none;"></span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="gender"><b>Gender<span style="color: red;">*</span> : </b></label>
                    <select class="form-control" id="gender">
                        <option value="">select gender</option>
                    </select>
                    <span id="gender_error" class="errorMessages" style="display: none;"></span>
                </div>

                <div class="col-md-6">
                    <label for="age"><b>Age<span style="color:red">*</span></b> :</label>
                    <input type="text" class="form-control" name="age" id="age" value="" placeholder="enter age...">
                    <span class="errorMessages" id="age_error" style="display: none;"></span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="state"><b>State</b><span style="color:red">*</span> :</label>
                    <select id="state" class="form-control">
                        <option value="">Select State</option>
                    </select>
                    <span class="errorMessages" style="display: none;" id="state_error">Please select your state.</span>
                </div>

                <div class="col-md-6">
                    <label for="city"><b>City</b><span style="color:red">*</span> :</label>
                    <select id="city" class="form-control">
                        <option value="">Select City</option>
                    </select>
                    <span class="errorMessages" style="display: none;" id="city_error">Please select your city.</span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="speciality"><b>Speciality<span style="color: red;">*</span> :</b></label>
                    <select class="form-control" id="speciality">
                        <option value="">select speciality</option>
                    </select>
                    <span id="speciality_error" class="errorMessages" style="display: none;"></span>
                </div>

                <div class="col-md-6">
                    <label for="licenseNumber"><b>Medical License Number <span style="color: red;">*</span> :</b></label>
                    <input type="text" name="licenseNumber" class="form-control" id="licenseNumber" value="" placeholder="enter medical license number...">
                    <span style="display: none;" id="licenseNumber_error" class="errorMessages"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    <button type="button" class="btn btn-success" id="submitForm"><i class="fas fa-save pr-1"></i>Save</button>
                </div>
            </div>
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
<script src="{{ asset('js/doctorRegister.js') }}"></script>
@endpush