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
                <a href='{{ route("admin.patients") }}'>
                    <button type="button" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-circle-left"></i>
                        Back
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <x-patient-edit-detail  :patientsData="$patientsData" :isHideSaveButton="$isHideSaveButton" :class="$class" />
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