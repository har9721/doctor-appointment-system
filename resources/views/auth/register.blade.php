<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Doctor Appointment System</title>
    <link rel="shortcut icon" href="{{URL::to('assets/images/doctor.png')}}" />

    <link rel="stylesheet" href="{{ asset('css/register.css')}}">
    <link href="{{URL::to('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ URL::to('assets/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- select 2 css -->
    <link rel="stylesheet" href="{{URL::to('assets/css/select2.min.css')}}">
    <style>
        .select2-container .select2-selection--single
        {
            height: 36px !important;
        }
    </style>
</head>
<body>
    <div class="form-container mt-2">
        <div class="d-flex justify-content-between mb-20 step-indicator">
            <div class="active">Step 1</div>
            <div>Step 2</div>
            <div>Step 3</div>
            <div>Step 4</div>
        </div>
        <form id="multiStepForm">
            <!-- Step 1 -->
            <div class="step active">
                <h2>Personal Information</h2>
                <span style="color: red;">Note : All the information is mandatory.</span>

                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="first_name">First Name :</label>
                        <input type="text" class="form-control" id="first_name" placeholder="enter first name..." autocomplete="off" value="">
                        <span class="error" id="first_name_error">Please enter your first name.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="last_name">Last Name :</label>
                        <input type="text" class="form-control" id="last_name" placeholder="enter last name..." autocomplete="off" value=""> 
                        <span class="error" id="last_name_error">Please enter your last name.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" class="form-control" id="email" placeholder="enter email..." autocomplete="off" value="">
                        <span class="error" id="email_error">Please enter your email.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="mobile">Mobile No :</label>
                        <input type="number" min:10 class="form-control" id="mobile" placeholder="enter mobile number..." autocomplete="off" maxlength="10" value="">
                        <span class="error" id="mobile_error">Please enter your mobile no.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="gender">Gender :</label>
                        <select id="gender" class="form-control">
                            <option>Select Gender</option>
                        </select>
                        <span class="error" id="gender_error">Please select your gender.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="age">Age :</label>
                        <input type="number" id="age" class="form-control" autocomplete="off" value="" placeholder="enter your age...">
                        <span class="error" id="age_error">Please enter your age.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="state">State :</label>
                        <select id="state" class="form-control">
                            <option selected disabled>Select State</option>
                        </select>
                        <span class="error" id="state_error">Please select your state.</span>
                    </div>

                    <div class="col-md-3 form-group">
                        <label for="city">City :</label>
                        <select id="city" class="form-control">
                            <option selected disabled>Select City</option>
                        </select>
                        <span class="error" id="city_error">Please select your city.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="address">Address :</label>
                        <textarea name="address" id="address" class="form-control" rows="1" autocomplete="off" placeholder="enter address..."></textarea>
                        <span class="error" id="address_error">Please enter your address.</span>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step">
                <h2>Emergency Contact Details</h2>
                <span style="color: red;">Note : All the information is mandatory.</span>
               
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="name">Name :</label>
                        <input type="text" id="name" class="form-control" placeholder="enter name..." autocomplete="off">
                        <span class="error" id="name_error">Please enter name.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="relation_with_contact">Relation with contact person :</label>
                        <input type="text" id="relation_with_contact" placeholder="enter relation" value="" autocomplete="off" class="form-control">
                        <span class="error" id="relation_with_contact_error">Please enter contact person relation.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="contact_no">Contact No :</label>
                        <input type="number" id="contact_no" class="form-control" placeholder="enter phone number..." autocomplete="off">
                        <span class="error" id="contact_no_error">Please enter contact no.</span>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step">
                <h2>Medical History</h2>
                <span style="color: red;">Note : All the information is mandatory.</span>

                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="past_illness">Past Illness :</label>
                        <textarea id="past_illness" class="form-control" placeholder="enter past illness..." autocomplete="off" rows="1"></textarea>
                        <span class="error" id="past_illness_error">Please enter your past illness.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="chronic_condition">Chronic Condition :</label>
                        <textarea id="chronic_condition" class="form-control" placeholder="enter chronic condition..." autocomplete="off" rows="1"></textarea>
                        <span class="error" id="chronic_condition_error">Plese enter your chronic condition.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="surgeries">Surgeries :</label>
                        <textarea id="surgeries" class="form-control" placeholder="enter surgeries..." autocomplete="off" rows="1"></textarea>
                        <span class="error" id="surgeries_error">Please enter your sugeries details.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="allergies">Allergies :</label>
                        <textarea id="allergies" class="form-control" placeholder="enter allergies..." autocomplete="off" rows="1"></textarea>
                        <span class="error" id="allergies_error">Please enter your allergies details.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="medication">Medication :</label>
                        <textarea id="medication" class="form-control" placeholder="enter medications..." autocomplete="off" rows="1"></textarea>
                        <span class="error" id="medication_error">Please enter your medication details.</span>
                    </div>
                </div>
            </div>

            <div class="step">
                <h2>Lifestyle Information</h2>
                <span style="color: red;">Note : All the information is mandatory.</span>

                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="smoking_status">Smoking Status :</label>
                        <select id="smoking_status" class="form-control">
                            <option value="" selected>Select Status</option>
                        </select>
                        <span class="error" id="smoking_status_error">Please select smoking status.</span>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="alcohol">Alcohol Consumption :</label>
                        <select id="alcohol" class="form-control">
                            <option value="" selected>Select Frequency</option>
                        </select>
                        <span class="error" id="alcohol_error">Please select alcohol consumption.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="exercise">Exercise :</label>
                        <textarea id="exercise" class="form-control" placeholder="enter exercise..." autocomplete="off" rows="1"></textarea>
                        <span class="error" id="exercise_error">Please enter excercise details.</span>
                    </div>
                </div>
            </div>

            <div class="form-buttons">
                <button type="button" class="btn btn-md btn-dark" id="prevBtn"><i class="fas fa-arrow-circle-left pr-1"></i>Previous</button>
                <button type="button" class="btn btn-md btn-dark" id="nextBtn"><i class="fas fa-arrow-circle-right pr-1"></i>Next</button>
                <button type="button" class="btn btn-md btn-dark" id="submit" style="display: none;"><i class="fas fa-save pr-1"></i>Save</button>
            </div>
        </form>
    </div>

    <script src="{{ URL::to('assets/js/jquery.min.js') }}"></script>
    <script src="{{URL::to('assets/js/sweetalert2.js')}}"></script>
    <!-- select2 -->
    <script src="{{URL::to('assets/js/select2.min.js')}}"></script>
    <script type="text/javascript">
        let getCity = "{{ route('get-city') }}";
        let getStates = "{{ route('get-state') }}";
        let getGender = "{{ route('get-gender') }}";
        let registerUser = "{{ route('register') }}";
        let smokingStatus = "{{ route('get-smoking-status') }}";
        let alcoholStatus = "{{ route('get-alcohol-status') }}";
        let loginUrl = "{{ route('/') }}";
    </script>
    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>