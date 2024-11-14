<div class="row">
    <div class="col-md-3">
        <input type="hidden" id="patients_ID" value="{{ $getLoginPatientsId ? $getLoginPatientsId->id : '' }}">
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