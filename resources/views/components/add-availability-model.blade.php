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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="hidden" id="hidden_date" value="">
                        <input type="hidden" id="hidden_login_user_id" value="{{ $id }}">
                        <input type="hidden" id="hidden_timeslot_id" value="">

                        <label for="start_time"><b>Start Time </b><span style="color: red;">*</span> :</label>
                        <input type="text" name="start_time" id="start_time" class="form-control datetimepicker" value="" placeholder="select time..." autocomplete="off" onkeydown="return false;">
                        <span class="errorMessage" id="start_time_error" style="display: none; color:red">Please select start time</span>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time"><b>End Time </b><span style="color: red;">*</span> :</label>
                        <input type="text" name="end_time" id="end_time" class="form-control datetimepicker" value="" placeholder="select time..." autocomplete="off" onkeydown="return false;">
                        <span class="errorMessage" id="end_time_error" style="display: none; color:red">Please select end time</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="recurrence"><b>Recurrence Pattern </b> :</label>
                        <select class="form-control" id="recurrence" name="recurrence">
                            <option value="" selected>Select Recurrence</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <div id="weeklyOptions" class="mb-1 d-none">
                            <label class="form-label"><b>Select Days</b> :</label>
                            <div class="d-flex flex-wrap mb-3">
                                @foreach($weekdays as $days)
                                    <div class="form-check me-3 mr-3">
                                        <input class="form-check-input" type="checkbox" id="{{ $days }}" value="{{ $days }}">
                                        <label class="form-check-label" for="{{ $days }}">{{ ucfirst($days) }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <span style="color: red;">Note : The selected time slot will be added for all the selected days of the current month.</span>
                        </div>
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
