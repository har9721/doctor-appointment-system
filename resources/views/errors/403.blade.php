@extends('layouts.app')

<style>
    .img_center{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 30%;
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Not Authorized</h4>
            <div class="text-right">
                <a href="{{ route('home') }}">
                    <button type="button" class="btn btn-dark mr-2" id="addDoctorBtn">
                        Back
                    </button>
                </a>
            </div>
        </div>
        <div class="card-body">
            <img src="{{ url('/images/notAuthorized.png')}}" alt="Not Authorized" width="40%" class="img_center">
            <h3 class="text-danger text-center p-10 pb-20">403 | YOU ARE NOT AUTHORIZED TO ACCESS THIS PAGE!</h3>
        </div>
    </div>
</div>
@endsection
