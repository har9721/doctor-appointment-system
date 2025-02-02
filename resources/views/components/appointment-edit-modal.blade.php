<div class="modal modal-md" id="edit_appointment" role="modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Edit Appointment Details</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <input type="hidden" id="appointment_id" value="">
                        <input type="hidden" id="patient_ID" value="">
                        <label for="city">City : <span style="color: red;">*</span></label>
                        <select name="city" id="city" class="form-control" style="width: 150px;">
                            <option value="">Select City name</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="speciality">Speciality : <span style="color: red;">*</span></label>
                        <select name="specialty" id="speciality" class="form-control">
                            <option value="">Select Speciality</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="doctor">Doctor : <span style="color: red;">*</span></label>
                        <select name="doctor" id="doctor" class="form-control" style="width: 150px;">
                            <option value="">Select Doctor</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="date">Date : <span style="color: red;">*</span></label>
                        <input type="text" id="appointment_date_for_edit" class="datetimepicker form-control appointment_date_for_edit" value="" placeholder="Select date" autocomplete="off" onkeydown="return false;">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <span style="color:red">Note : Please select date to see the available time slots or change the details.</span></br>
                        <div id="timeSlotDivForEdit" class="d-flex flex-wrap align-content-start mt-2">
                            <label for="timeSlotDivForEdit" class="mt-2">Available Time Slots :</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-4 text-center">
                        <button class="btn btn-success form-group mt-2" id="searchForTimeslot">Search</button>
                    </div>
                </div>

                <!-- Search Results -->
                <div id="searchResults" class="search-results row mt-4">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success confirmAppointment" id="confirmAppointment">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
