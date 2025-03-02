@extends('layouts.app')
@push('css')
    <style>
        .errorMessages{
            color: red;
        }
        .imageThumb {
            max-height: 75px;
            border: 2px solid;
            border-radius: 50%;
            padding: 1px;
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">My Profile</h4>
            <div class="text-right">
                <a href="{{ route('home') }}">
                    <button type="button" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-circle-left"></i>
                        Back
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form id="profileForm" enctype="multipart/form-data">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="first_name"><b>First Name<span style="color: red;">*</span> : </b></label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="enter first name..." value="{{ $user['first_name'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="last_name"><b>Last Name<span style="color: red;">*</span> :</b></label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="enter last name..." value="{{ $user['last_name'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="email"><b>Email<span style="color: red;">*</span> :</b></label>
                        <input type="email" id="email" disabled name="email" class="form-control" placeholder="enter email..." value="{{ $user['email'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="mobile"><b>Mobile<span style="color: red;">*</span> :</b></label>
                        <input type="number" id="mobile" name="mobile" class="form-control" placeholder="enter mobile..." value="{{ $user['mobile'] }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="gender"><b>Gender<span style="color: red;">*</span> : </b></label>
                        <select class="form-control" id="gender_ID" name="gender_ID">
                            <option value="">select gender</option>
                        </select>
                        <input type="hidden" id="hidden_gender_ID" value="{{ $user['gender_ID'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="age"><b>Age<span style="color:red">*</span></b> :</label>
                        <input type="text" class="form-control" name="age" id="age" placeholder="enter age..." value="{{ $user['age'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="state"><b>State</b><span style="color:red">*</span> :</label>
                        <select id="state" class="form-control" name="state">
                            <option value="">Select State</option>
                        </select>
                        <input type="hidden" id="hidden_state_ID" value="{{ $user->city['state_id'] }}">
                    </div>

                    <div class="col-md-3">
                        <label for="city"><b>City</b><span style="color:red">*</span> :</label>
                        <select id="city_ID" class="form-control" name="city_ID">
                            <option value="">Select City</option>
                        </select>
                        <input type="hidden" id="hidden_city_ID" value="{{ $user['city_ID'] }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-success" id="submitUserForm"><i class="fas fa-save pr-1"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let saveUserDetails = "{{ route('admin.upateUserDetails') }}";
    let getGender = "{{ route('get-gender') }}";
    let getCity = "{{ route('get-city') }}";
    let getStates = "{{ route('get-state') }}";
    let getSpecialty = "{{ route('specialtyList') }}"
    let getDoctorList = "{{ route('admin.doctor') }}"
</script>
<script src="{{ asset('js/userProfile.js') }}"></script>
@endpush