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
            <h4 class="mt-2 font-weight-bold text-primary">Patients Details</h4>
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
            @if(Auth::user()->role->roleName === 'Admin')
                <form id="patientEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="isPatients" value="0">
                    <input type="hidden" name="user_ID" value="{{ $patientsData['user_ID'] }}">

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="first_name"><b>First Name<span style="color: red;">*</span> : </b></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="enter first name..." value="{{ $patientsData->user->first_name ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label for="last_name"><b>Last Name<span style="color: red;">*</span> :</b></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="enter last name..." value="{{ $patientsData->user->last_name ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label for="email"><b>Email<span style="color: red;">*</span> :</b></label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="enter email..." value="{{ $patientsData->user->email ?? '' }}" @if(Auth::user()->role_ID != 1) disabled @endif>
                        </div>

                        <div class="col-md-3">
                            <label for="mobile"><b>Mobile<span style="color: red;">*</span> :</b></label>
                            <input type="number" id="mobile" name="mobile" class="form-control" placeholder="enter mobile..." value="{{ $patientsData->user->mobile ?? ''}}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="gender"><b>Gender<span style="color: red;">*</span> : </b></label>
                            <select class="form-control" id="gender_ID" name="gender_ID">
                                <option value="">select gender</option>
                            </select>
                            <input type="hidden" id="hidden_gender_ID" value="{{ $patientsData->user->gender_ID ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label for="age"><b>Age<span style="color:red">*</span></b> :</label>
                            <input type="text" class="form-control" name="age" id="age" placeholder="enter age..." value="{{ $patientsData->user->age ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label for="state"><b>State</b><span style="color:red">*</span> :</label>
                            <select id="state" class="form-control" name="state">
                            </select>
                            <input type="hidden" id="hidden_state_ID" value="{{ $patientsData->user->city->state_id ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label for="city"><b>City</b><span style="color:red">*</span> :</label>
                            <select id="city_ID" class="form-control" name="city_ID">
                            </select>
                            <input type="hidden" id="hidden_city_ID" value="{{ $patientsData->user->city_ID ?? '' }}">
                        </div>
                    </div>

                    <div class="row mb-4"> 
                        <div class="col-md-4">
                            <label for="address"><b>Address <span style="color: red;">*</span> :</b></label>
                            <textarea type="text" name="address" class="form-control" id="address" placeholder="enter address..." value="{{ $patientsData->user->address ?? '' }}">{{ $patientsData->user->address ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                            <button class="btn btn-success" id="submitForm"><i class="fas fa-save pr-1"></i>Save</button>
                        </div>
                    </div>
                </form>
            @else
                <x-patient-edit-detail  :patientsData="$patientsData" :isHideSaveButton="$isHideSaveButton" :class="$class" />
            @endif
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    let savePatientsData = "{{ route('admin.patientsUpdate') }}";
    let getGender = "{{ route('get-gender') }}";
    let getCity = "{{ route('get-city') }}";
    let getStates = "{{ route('get-state') }}";
    let getPatientList = "{{route('admin.get-patients')}}";
    let patientsList = "{{route('admin.patients')}}";
    let smokingStatus = "{{ route('get-smoking-status') }}";
    let alcoholStatus = "{{ route('get-alcohol-status') }}";
</script>
<script src="{{ asset('js/Patient/patients.js') }}"></script>
@endpush