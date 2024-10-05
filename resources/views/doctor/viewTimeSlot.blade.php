@extends('layouts.app')
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

<!-- --------------------manage availability modal-------------- -->
<div class="modal modal-md" id="manageAvailability" tabindex="-1" role="modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Add Doctor Availability</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" id="hidden_date" value="">
                        <input type="hidden" id="hidden_login_user_id" value="{{ $loginUserId->id }}">
                        <input type="hidden" id="hidden_timeslot_id" value="">

                        <label for="start_time">Start Time <span style="color: red;">*</span> :</label>
                        <input type="text" name="start_time" id="start_time" class="form-control datetimepicker" value="" placeholder="select time..." autocomplete="off">
                        <span class="errorMessage" id="start_time_error" style="display: none; color:red">Please select start time</span>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time">End Time <span style="color: red;">*</span> :</label>
                        <input type="text" name="end_time" id="end_time" class="form-control datetimepicker" value="" placeholder="select time..." autocomplete="off">
                        <span class="errorMessage" id="end_time_error" style="display: none; color:red">Please select end time</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submit">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ---------------------action modal----------------------- -->
<div class="modal modal-md" id="actionModal" tabindex="-1" role="modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Modify Doctor Availability</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="start_time" class="ml-3">Please select action that you want to perform on the event<span style="color: red;">*</span> :</label>
                    <div class="col-md-6">
                        <select class="form-control" id="action">
                            <option value="">Select Action</option>
                            <option value="edit">Edit</option>
                            <option value="delete">Delete</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitAction">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let fetchAllEvents = '{{ route("doctor.getTimeSlot") }}';
    let addTimeSlot = '{{ route("doctor.addTimeSlot") }}';
    let deleteTimeSlot = '{{ route("doctor.deleteTimeSlot") }}';
    let updateTimeSlot = '{{ route("doctor.updateTimeSlot") }}';
</script>
<script src="{{ asset('js/viewTimeSlot.js') }}"></script>
@endsection