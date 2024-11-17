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
                        <input type="hidden" id="hidden_login_user_id" value="{{ $id }}">
                        <input type="hidden" id="hidden_timeslot_id" value="">

                        <label for="start_time">Start Time <span style="color: red;">*</span> :</label>
                        <input type="text" name="start_time" id="start_time" class="form-control datetimepicker" value="" placeholder="select time..." autocomplete="off" onkeydown="return false;">
                        <span class="errorMessage" id="start_time_error" style="display: none; color:red">Please select start time</span>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time">End Time <span style="color: red;">*</span> :</label>
                        <input type="text" name="end_time" id="end_time" class="form-control datetimepicker" value="" placeholder="select time..." autocomplete="off" onkeydown="return false;">
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
