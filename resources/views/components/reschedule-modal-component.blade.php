<div class="modal modal-md" id="rescheduleModal" tabindex="-1" role="modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Reschedule Appointment</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="hidden_appointment_id" id="hidden_appointment_id">
                <input type="hidden" name="hidden_doctor_id" id="hidden_doctor_id">
                <input type="hidden" name="hidden_patient_id" id="hidden_patient_id">
                <input type="hidden" name="hidden_timeslot_id" id="hidden_timeslot_id">
                <input type="hidden" name="hidden_appointment_date" id="hidden_appointment_date">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="date">Appointment Date<span style="color: red;">*</span> :</label>
                        <input type="text" name="appointment_date" class="form-control" placeholder="select date..." id="appointment_date" onkeydown="return false;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="date">Available Time Slot<span style="color: red;">*</span> :</label>
                        <div id="timeSlotDiv" class="time-slots-container">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submit" title="please select time slot">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>