@extends('layouts.app')
@push('css')
    <style>
        .errorMessages{
            color: red;
        }
        .imageThumb {
            max-height: 80px;
            border: 2px solid;
            border-radius: 50%;
            padding: 1px;
            cursor: pointer;
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
            <h4 class="mt-2 font-weight-bold text-primary">{{ $heading }}</h4>
            <div class="text-right">
                <a href='{{ route("$backUrl") }}'>
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
                <input type="hidden" name="user_ID" value="{{ $doctorDetails['user_ID'] }}">
                <div class="row mb-4">
                    <div class="col-md-8 col-md-9 col-sm-12 preview-area">
                        <?php $location = "doctorProfilePictures/".$doctorDetails['fileName']; ?>
                        <img class="imageThumb img-thumbnail" style="width: 100px; height: 100px; cursor: pointer;"  src="{{ Storage::url($location) }}" alt="Profile Image" data-bs-toggle="modal" 
                        data-bs-target="#viewDoctorPictureModal">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="first_name"><b>First Name<span style="color: red;">*</span> : </b></label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="enter first name..." value="{{ $doctorDetails['first_name'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="last_name"><b>Last Name<span style="color: red;">*</span> :</b></label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="enter last name..." value="{{ $doctorDetails['last_name'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="email"><b>Email<span style="color: red;">*</span> :</b></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="enter email..." value="{{ $doctorDetails['email'] }}" @if(Auth::user()->role_ID != 1) disabled @endif>
                    </div>

                    <div class="col-md-3">
                        <label for="mobile"><b>Mobile<span style="color: red;">*</span> :</b></label>
                        <input type="number" id="mobile" name="mobile" class="form-control" placeholder="enter mobile..." value="{{ $doctorDetails['mobile'] }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="gender"><b>Gender<span style="color: red;">*</span> : </b></label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">select gender</option>
                        </select>
                        <input type="hidden" id="hidden_gender_ID" value="{{ $doctorDetails['gender_ID'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="age"><b>Age<span style="color:red">*</span></b> :</label>
                        <input type="text" class="form-control" name="age" id="age" placeholder="enter age..." value="{{ $doctorDetails['age'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="state"><b>State</b><span style="color:red">*</span> :</label>
                        <select id="state" class="form-control" name="state">
                            <!-- <option value="">Select State</option> -->
                        </select>
                        <input type="hidden" id="hidden_state_ID" value="{{ $doctorDetails['state_ID'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="city"><b>City</b><span style="color:red">*</span> :</label>
                        <select id="city" class="form-control" name="city">
                            <!-- <option value="">Select City</option> -->
                        </select>
                        <input type="hidden" id="hidden_city_ID" value="{{ $doctorDetails['city_ID'] }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="speciality"><b>Speciality<span style="color: red;">*</span> :</b></label>
                        <select class="form-control" id="speciality" name="speciality">
                            <option value="">select speciality</option>
                        </select>
                        <input type="hidden" id="hidden_specialty_ID" value="{{ $doctorDetails['specialty_ID'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="licenseNumber"><b>Medical License Number <span style="color: red;">*</span> :</b></label>
                        <input type="text" name="licenseNumber" class="form-control" id="licenseNumber" placeholder="enter medical license number..." value="{{ $doctorDetails['licenseNumber'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="experience"><b>Experience<span style="color: red;">*</span> :</b></label>
                        <input type="number" class="form-control" name="experience" id="experience" placeholder="enter experience" value="{{ $doctorDetails['experience'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="consultationFees">
                            <b>Consultation Fees<span style="color: red;">*</span> :</b>
                        </label>
                        <input type="number" class="form-control" name="consultationFees" id="consultationFees" value="{{ $doctorDetails['consultationFees'] }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="followUpFees">
                            <b>Follow Up Fees<span style="color: red;">*</span> :</b>
                        </label>
                        <input type="number" class="form-control" name="followUpFees" id="followUpFees" value="{{ $doctorDetails['followUpFees'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="paymentMode">
                            <b>Payment Mode<span style="color: red;">*</span> :</b>
                        </label>

                        @php
                            $paymentMode = $doctorDetails['paymentMode'];
                            $noneSelected = $paymentMode == 'none' ? 'selected' : '';
                            $advanceSelected = $paymentMode == 'advance' ? 'selected' : '';
                            $fullSelected = $paymentMode == 'full' ? 'selected' : '';
                        @endphp

                        <select class="form-control" id="paymentMode" name="paymentMode" value="{{ $doctorDetails['paymentMode'] }}">
                            <option value="" disabled>select payment mode</option>
                            <option value="none" {{ $noneSelected }}>None</option>
                            <option value="advance" {{ $advanceSelected }}>Advance</option>
                            <option value="full" {{ $fullSelected }}>Full</option>
                        </select>
                    </div>

                    <div class="col-md-3" id="advance_fees_input_div">
                        <label for="advanceFees">
                            <b>Advance Fees :</b>
                        </label>
                        <input type="number" class="form-control" name="advanceFees" id="advanceFees" placeholder="enter advance fees" value="{{ $doctorDetails['advanceFees'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="profileImageRadioBtn"><b>Do you want to update profile Image:</label><br/>
                        <label class="radio-inline">
                            <input type="radio" name="imageUpdateOption" value="Yes">Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="imageUpdateOption" value="No" checked>No
                        </label>
                    </div>
                </div>

                <div class="row mb-4" id="fileUploadDiv" style="display: none;">
                    <div class="col-md-3">
                        <label for="image"><b>Profile Image <span style="color: red;">*</span> :</b></label>
                        <input type="file" name="profile_image" class="form-control" id="image" value="{{ $doctorDetails['fileName'] }}" >
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

<x-view-profile-image :image="$location"/>

@endsection
@push('scripts')
<script>
    let saveDoctorDetails = "{{ route('admin.doctorUpdate') }}";
    let getGender = "{{ route('get-gender') }}";
    let getCity = "{{ route('get-city') }}";
    let getStates = "{{ route('get-state') }}";
    let getSpecialty = "{{ route('specialtyList') }}"
    let getDoctorList = "{{ route('admin.doctor') }}"
</script>
<script src="{{ asset('js/Doctor/doctorRegister.js') }}"></script>
@endpush