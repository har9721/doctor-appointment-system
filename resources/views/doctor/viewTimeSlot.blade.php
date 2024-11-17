@extends('layouts.app')
<style>
    .fc-daygrid-day-bottom{
        padding-bottom: 20px;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h4 class="mt-2 font-weight-bold text-primary">Time Slot</h4>
            <div class="text-right">
            </div>
        </div>
        <div class="card-body"> 
            <!-- <div class="container ro"> -->
                <div id="calendar"></div>
            <!-- </div> -->
        </div>
    </div>
</div>

<x-add-availability-model :id="$loginUserId->id"/>

<!-- ---------------------action modal----------------------- -->
<x-availability-action-model :title="'Modify'" :options="[]"/>

<script>
    let fetchAllEvents = '{{ route("doctor.getTimeSlot") }}';
    let addTimeSlot = '{{ route("doctor.addTimeSlot") }}';
    let deleteTimeSlot = '{{ route("doctor.deleteTimeSlot") }}';
    let updateTimeSlot = '{{ route("doctor.updateTimeSlot") }}';
</script>
<script src="{{ asset('js/Doctor/viewTimeSlot.js') }}"></script>
@endsection