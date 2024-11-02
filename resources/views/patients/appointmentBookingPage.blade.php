@extends('layouts.app')
<style>
    .container {
            margin-top: 30px;
        }

        .search-section input {
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1.2rem;
        }

        .search-results {
            margin-top: 30px;
        }

        .doctor-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
        }

        .doctor-card img {
            border-radius: 10px 10px 0 0;
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .doctor-info {
            padding: 15px;
            text-align: center;
        }

        .doctor-info h5 {
            font-weight: 600;
        }

        .doctor-info p {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .accordion .card-header {
            cursor: pointer;
        }

        .time-slot {
            padding: 10px 20px;
            margin-bottom: 10px;
            border-radius: 30px;
            background-color: #f7f9fc;
            border: 1px solid #007bff;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #007bff;
        }

        .time-slot:hover {
            background-color: #007bff;
            color: white;
        }

        .selected {
            background-color: #007bff !important;
            color: white !important;
        }

        .book-btn {
            margin-top: 20px;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1.2rem;
            font-weight: 600;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .book-btn:hover {
            background-color: #0056b3;
        }

        /* Custom flexbox for time slots */
        .time-slots-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .accordion-body {
            max-width: 100%;
            overflow-x: auto;
        }

        .select2-container .select2-selection--single
        {
            height: 36px !important;
        }
</style>
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Appointment Booking</h4>
            <div class="text-right">
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <input type="hidden" id="patients_ID" value="{{ $getLoginPatientsId->id }}">
                    <label for="speciality">Speciality : </label>
                    <select name="specialty" id="speciality" class="form-control">
                        <option value="">Select Speciality</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="city">City : </label>
                    <select name="city" id="city" class="form-control">
                        <option value="">Select City</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date">Date : <span style="color: red;">*</span></label>
                    <input type="text" id="date" class="datetimepicker form-control" value="<?php echo date('d-m-Y') ?>" onkeydown="return false;">
                </div>
                <div class="col-md-3 mt-4">
                    <button class="btn btn-success form-group mt-2" id="search">Search</button>
                </div>
            </div>

            <!-- Search Results -->
            <div id="searchResults" class="search-results row mt-4">
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let getCity = "{{ route('get-city') }}";
    let getSpecialty = "{{ route('admin.specialtyList') }}";
    let searchDoctor = "{{ route('patients.search-doctor') }}";
    let bookingUrl = "{{ route('patients.book-appointment') }}";
</script>
<script src="{{ asset('js/Appointments/appointmentBook.js') }}"></script>
@endpush