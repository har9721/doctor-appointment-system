<div class="row mb-4">
    <div class="col-md-3">
        <label for="patients">Patients : <span style="color: red;">*</span></label>
        <select name="patients" id="patients" class="form-control">
            <option value="">Select Patients</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="hidden" id="patients_ID" value="{{ $getLoginPatientsId ? $getLoginPatientsId->id : '' }}">
        <label for="speciality">Speciality : <span style="color: red;">*</span></label>
        <select name="specialty" id="speciality" class="form-control">
            <option value="">Select Speciality</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="doctor">Doctor : <span style="color: red;">*</span></label>
        <select name="doctor" id="doctor" class="form-control">
            <option value="">Select Doctor</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="date">Date : <span style="color: red;">*</span></label>
        <input type="text" id="date" class="datetimepicker form-control" value="" placeholder="Select date"   autocomplete="off" onkeydown="return false;">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <span style="color:red">Note : Please select date to see the available time slots.</span></br>
        <div id="timeSlotDiv" class="d-flex flex-wrap align-content-start">
            <label for="timeSlotDiv" class="mt-2">Available Time Slots :</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-4 text-center">
        <button class="btn btn-success form-group mt-2" id="searchForTimeslot">Search</button>
        <button style="visibility: hidden;" class="btn btn-info form-group mt-2" id="confirmAppointment">Confirm Appointment</button>
    </div>
</div>

<!-- Search Results -->
<div id="searchResults" class="search-results row mt-4">
</div>