<form id="patientEditForm" enctype="multipart/form-data">
    <input type="hidden" name="isPatients" value="1">
    <input type="hidden" name="user_ID" value="{{ $patientsData['user_ID'] }}">

    <fieldset class="border border-warning p-3 rounded mb-4">
        <legend class="float-none w-auto px-3 text-success">Personal Information :</legend>

        <div class="row mb-4">
            <div class="col-md-3">
                <label for="first_name"><b>First Name<span style="color: red;">*</span> : </b></label>
                <input type="text" class="form-control" {{ $class }} name="first_name" id="first_name" placeholder="enter first name..." value="{{ $patientsData->user->first_name ?? '' }}">
            </div>
    
            <div class="col-md-3">
                <label for="last_name"><b>Last Name<span style="color: red;">*</span> :</b></label>
                <input type="text" class="form-control" {{ $class }}  name="last_name" id="last_name" placeholder="enter last name..." value="{{ $patientsData->user->last_name ?? '' }}">
            </div>
    
            <div class="col-md-3">
                <label for="email"><b>Email<span style="color: red;">*</span> :</b></label>
                <input type="email" id="email" {{ $class }}  name="email" class="form-control" placeholder="enter email..." value="{{ $patientsData->user->email ?? '' }}" @if(Auth::user()->role_ID != 1) disabled @endif>
            </div>
    
            <div class="col-md-3">
                <label for="mobile"><b>Mobile<span style="color: red;">*</span> :</b></label>
                <input type="number" id="mobile" {{ $class }}  name="mobile" class="form-control" placeholder="enter mobile..." value="{{ $patientsData->user->mobile ?? ''}}">
            </div>
        </div>
    
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="gender"><b>Gender<span style="color: red;">*</span> : </b></label>
                <select class="form-control" {{ $class }}  id="gender_ID" name="gender_ID">
                    <option value="">select gender</option>
                </select>
                <input type="hidden" id="hidden_gender_ID" value="{{ $patientsData->user->gender_ID ?? '' }}">
            </div>
    
            <div class="col-md-3">
                <label for="age"><b>Age<span style="color:red">*</span></b> :</label>
                <input type="text" class="form-control" {{ $class }}  name="age" id="age" placeholder="enter age..." value="{{ $patientsData->user->age ?? '' }}">
            </div>
    
            <div class="col-md-3">
                <label for="state"><b>State</b><span style="color:red">*</span> :</label>
                <select id="state" {{ $class }}  class="form-control" name="state">
                </select>
                <input type="hidden" id="hidden_state_ID" value="{{ $patientsData->user->city->state_id ?? '' }}">
            </div>
    
            <div class="col-md-3">
                <label for="city"><b>City</b><span style="color:red">*</span> :</label>
                <select id="city_ID" {{ $class }}  class="form-control" name="city_ID">
                </select>
                <input type="hidden" id="hidden_city_ID" value="{{ $patientsData->user->city_ID ?? '' }}">
            </div>
        </div>
    
        <div class="row mb-4"> 
            <div class="col-md-4">
                <label for="address"><b>Address <span style="color: red;">*</span> :</b></label>
                <textarea type="text" {{ $class }}  name="address" class="form-control" id="address" placeholder="enter address..." value="{{ $patientsData->user->address ?? '' }}">{{ $patientsData->user->address ?? '' }}</textarea>
            </div>
        </div>
    </fieldset>

    <fieldset class="border border-warning p-3 rounded mb-4">
        <input type="hidden" name="emergency_contact_id" id="emergency_contact_id" value="{{ optional($patientsData->emergencyContact)->id }}">

        <legend class="float-none w-auto px-3 text-success">Emergency Contact Details : </legend>

        <div class="row mb-4">
            <div class="col-md-3">
                <label for="name"><b>Name<span style="color: red;">*</span> : </b></label>
                <input type="text" {{ $class }}  class="form-control" id="name" name="name" value="{{ optional($patientsData->emergencyContact)->contact_name }}" placeholder="enter name">
            </div>
    
            <div class="col-md-3">
                <label for="contact_relation"><b>Relation with contact person<span style="color: red;">*</span> :</b></label>
                <input type="text"  {{ $class }} class="form-control" id="contact_relation" value="{{ optional($patientsData->emergencyContact)->contact_relation }}" name="contact_relation" placeholder="enter relation with contact">
            </div>
    
            <div class="col-md-3">
                <label for="contact_no"><b>Contact No<span style="color: red;">*</span> :</b></label>
                <input type="text" {{ $class }}  class="form-control" id="contact_no" value="{{ optional($patientsData->emergencyContact)->phone_no }}" name="contact_no" placeholder="enter contact no">
            </div>
        </div>
    </fieldset>


    <fieldset class="border border-warning p-3 rounded mb-4">
        <legend class="float-none w-auto px-3 text-success">Medical History</legend>

        <input type="hidden" name="medical_history_id" id="medical_history_hidden_id" value="{{ optional($patientsData->medicalHistory)->id }}">

        <div class="row mb-4">
            <div class="col-md-3">
                <label for=""><b>Past Illness<span style="color: red;">*</span> :</b></label>
                <textarea id="past_illness" {{ $class }}  rows="1" name="past_illness" class="form-control">{{ optional($patientsData->medicalHistory)->illness }}</textarea>
            </div>
            <div class="col-md-3">
                <label for=""><b>Chronic Condition<span style="color: red;">*</span> :</b></label>
                <textarea id="chronic_condition" {{ $class }}  rows="1" name="chronic_condition" class="form-control">{{ optional($patientsData->medicalHistory)->chronicDisease }}</textarea>
            </div>
            <div class="col-md-3">
                <label for=""><b>Surgeries<span style="color: red;">*</span> : </b></label>
                <textarea id="surgeries" {{ $class }}  rows="1" name="surgeries" class="form-control">{{ optional($patientsData->medicalHistory)->surgery }}</textarea>
            </div>
            <div class="col-md-3">
                <label for="allergies"><b>Allergies<span style="color: red;">*</span> : </b></label>
                <textarea id="allergies" {{ $class }}  name="allergies" rows="1" class="form-control">{{ optional($patientsData->medicalHistory)->allergies }}</textarea>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-3">
                <label for="medication"><b>Medication<span style="color: red;">*</span> :</b></label>
                <textarea name="medication" {{ $class }}  rows="1" class="form-control" id="medication">{{ optional($patientsData->medicalHistory)->medication }}</textarea>
            </div>
        </div>
    </fieldset>

    <fieldset class="border border-warning p-3 rounded mb-4">
        <legend class="float-none w-auto px-3 text-success">Lifestyle Information : </legend>

        <input type="hidden" id="lifestyle_hidden_id" name="lifestyle_hidden_id" value="{{ optional($patientsData->lifeStyleInformation)->id }}">

        <div class="row">
            <div class="col-md-3">
                <label for="smoking_status"><b>Smoking Status<span style="color: red;">*</span> :</b></label>
                <select name="smoking_status" {{ $class }}  id="smoking_status" class="form-control">
                </select>

                <input type="hidden" name="smoking_status_hidden_id" id="smoking_status_hidden_id" value="{{ optional($patientsData->lifeStyleInformation)->smokingStatus_ID }}">
            </div>

            <div class="col-md-3">
                <label for="alcohol_consumption"><b>Alcohol Consumption<span style="color: red;">*</span> :</b></label>
                <select name="alcohol_status" {{ $class }}  rows="1" id="alcohol_status" class="form-control">
                </select>

                <input type="hidden" name="alcohol_status_hidden_id" id="alcohol_status_hidden_id" value="{{ optional($patientsData->lifeStyleInformation)->alcoholStatus_ID }}">
            </div>

            <div class="col-md-3">
                <label for="exercise"><b>Exercise<span style="color: red;">*</span> : </b></label>
                <textarea name="exercise"  {{ $class }} id="exercise" rows="1" class="form-control"> {{ optional($patientsData->lifeStyleInformation)->exercise }}</textarea>
            </div>
        </div>
    </fieldset>

    @if(Route::currentRouteName() !== 'admin.view-patient-history')
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <button class="btn btn-success" id="submitForm"><i class="fas fa-save pr-1"></i>Save</button>
            </div>
        </div>
    @endif
</form>