
let currentStep = 0;
const steps = document.querySelectorAll('.step');
const stepIndicators = document.querySelectorAll('.step-indicator div');
const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');

let first_name = '',last_name= '',email = '',mobile = '',gender = '',age = '',city = '',address = '',name = '',contact_relation = '',contact_no = ''; past_illness = '', chronic_condition = '', surgeries = '',allergies = '',medication = '', smoking_status = '', alcohol_status = '',exercise = '';

getStateList();
getGenderList();
getSmokingStatus();
getAlcoholStatus();

showStep(currentStep);

function showStep(step) {
    steps[step].classList.add('active');
    stepIndicators[step].classList.add('active');
    prevBtn.style.display = step === 0 ? 'none' : 'inline';
    // nextBtn.textContent = step === steps.length - 1 ? 'Submit' : 'Next';

    if(step == steps.length -1)
    {
        $('#submit').css('display','block');
        $('#nextBtn').css('display','none');
    }
}

function nextPrev(n) {
    steps[currentStep].classList.remove('active');
    stepIndicators[currentStep].classList.remove('active');
    
    currentStep += n;

    if (currentStep >= steps.length) {
        document.getElementById('multiStepForm').submit();
        return;
    }

    showStep(currentStep);
}

function validateForm(stepNo)
{
    let errorTrue = false;

    if(stepNo == '0')
    {
        first_name = $('#first_name').val();
        last_name = $('#last_name').val();
        email = $('#email').val();
        mobile = $('#mobile').val();
        gender = $('#gender').find(':selected').val();
        age = $('#age').val();
        state = $('#state').find(':selected').val();
        city = $('#city').find(':selected').val();
        address = $('#address').val();      
        
        if(first_name.trim() != '' && last_name.trim() != '' && email.trim() != '' && email.includes('@') && mobile.trim() != '' && mobile.length == 10 && gender != 'Select Gender' && age != '' && state != 'Select State' && city != 'Select City' && address.trim() != '')
        {
            errorTrue = true;
        }else{
            errorTrue = false;
        }

        if(first_name.trim() == '')
            $('#first_name_error').css('display','block');
        
        if(last_name.trim() == '')
            $('#last_name_error').css('display','block');

        if(email.trim() == '')
            $('#email_error').css('display','block');

        if(email.length > 3 && !email.includes('@'))
            $('#email_error').css('display','block').text('email must have @');

        if(mobile.trim() == '')
            $('#mobile_error').css('display','block');

        else if(mobile.length < 10 || mobile.length > 10)
            $('#mobile_error').css('display','block').text('Mobile no must be 10 digits.');

        if(gender == 'Select Gender')
            $('#gender_error').css('display','block');

        if(age.trim() == '')
            $('#age_error').css('display','block');

        if(state == 'Select State')
            $('#state_error').css('display','block');

        if(city == 'Select City')
            $('#city_error').css('display','block');

        if(address.trim() == '')
            $('#address_error').css('display','block');

        return errorTrue;
    }
    else if(stepNo == '1')
    {
        name = $('#name').val();
        contact_relation = $('#relation_with_contact').val();
        contact_no = $('#contact_no').val();

        if(name.trim() == '')
        {
            $('#name_error').css('display','block');
        }
        else if(name.length < 3){
            $('#name_error').css('display','block').text('Name must be greater than 3 characters.');
        }

        if(contact_relation.trim() == '')
        {
            $('#relation_with_contact_error').css('display','block');
        }
        else if(contact_relation.length <= 3)
        {
            $('#relation_with_contact_error').css('display','block').text('Contact relation must be greater than 3 characters.');
        }

        if(contact_no.trim() == '')
        {
            $('#contact_no_error').css('display','block');
        }
        else if(contact_no.length < 10 || contact_no.length > 10)
        {
            $('#contact_no_error').css('display','block').text('Contact no must be 10 digit.');
        }

        if(name.trim() != '' && contact_relation.trim() != '' && contact_no.length == 10)
        {
            errorTrue = true;
        }else{
            errorTrue = false;
        }

        return errorTrue;
    }
    else if(stepNo == '2')
    {
        past_illness = $('#past_illness').val();
        chronic_condition = $('#chronic_condition').val();
        surgeries = $('#surgeries').val();
        allergies = $('#allergies').val();
        medication = $('#medication').val();

        if(past_illness.trim() == '')
            $('#past_illness_error').css('display','block');
        else if(past_illness.length < 2)
            $('#past_illness_error').css('display','block').text('Pass illness must be greater than 2 characters.');

        if(chronic_condition.trim() == '')
            $('#chronic_condition_error').css('display','block');
        else if(chronic_condition.length < 2)
            $('#chronic_condition_error').css('display','block').text('Chronic condition must be greater than 2 characters.');

        if(surgeries.trim() == '')
            $('#surgeries_error').css('display','block');
        else if(surgeries.length < 2)
            $('#surgeries_error').css('display','block').text('Surgeries must be greater than 2 characters.');

        if(allergies.trim() == '')
            $('#allergies_error').css('display','block');
        else if(allergies.length < 2)
            $('#allergies_error').css('display','block').text('Allergies must be greater than 2 characters.');

        if(medication.trim() == '')
            $('#medication_error').css('display','block');
        else if(medication.length < 2)
            $('#medication_error').css('display','block').text('Medicaion must be greater than 2 characters.');

        if(past_illness.trim() != '' && chronic_condition.trim() != '' && surgeries.trim() != '' && allergies.trim() && medication.trim() != '')
        {
            return errorTrue = true;
        }else{
            return errorTrue = false;
        }
    }
}

// nextBtn.addEventListener('click', () => nextPrev(1));
// prevBtn.addEventListener('click', () => nextPrev(-1));

nextBtn.addEventListener('click', function(){
    let validated = validateForm(currentStep);
    
    (validated) ? nextPrev(1) : '';
});

prevBtn.addEventListener('click', function(){
    if(currentStep < 4)
    {
        $('#submit').css('display','none');
        $('#nextBtn').css('display','block');
    }       

    nextPrev(-1);
});

$(document).on('keyup', 'input[type=text]',function(){
    let id = $(this).attr('id');

    let getValue = $('#'+id).val();
    let newName = getValue.trim();

    if(newName != '' && newName.length <= 3){
        $('#'+id+'_error').css('display','none');
        $('#'+id+'_error').text('Name must be greater than 3 charatcters.');
    }
    else
        $('#'+id+'_error').css('display','none');
});

$(document).on('keyup','input[type=email]' ,function () {
    let email = $(this).val();

    if(email.length > 3 && email.includes('@'))
        $('#email_error').css('display','none');
})

$(document).on('keyup','input[type=number]', function(){
    let mobile = $(this).val();
    let id = $(this).attr('id');

    if(mobile.length == 10)
        $('#'+id+'_error').css('display','none');

    if(mobile.length >= 1)
        $('#'+id+'_error').css('display','none');
})

// ----------text area --------------------
$(document).on('keyup','textarea', function(){
    let text = $(this).val();
    let id = $(this).attr('id');

    if(text.length >= 2)
        $('#'+ id+'_error').css('display','none');
})

$(document).on('change','select', function(){
    let dropdown = $(this).attr('id');
    let dropdownValue = $('#'+dropdown).val();

    if(dropdownValue.length != 0)
        $('#'+dropdown+'_error').css('display','none');
})

$(document).on('change', '#state',function(){
    let state_ID = $(this).val();
    getCityList(state_ID); 
});

// submit the form
$(document).on('click','#submit', function(){
    smoking_status = $('#smoking_status').find(':selected').val();
    alcohol_status = $('#alcohol').find(':selected').val();
    exercise = $('#exercise').val();

    if(smoking_status == '')
        $('#smoking_status_error').css('display','block');
    
    if(alcohol_status == '')
        $('#alcohol_error').css('display','block');

    if(exercise == '')
        $('#exercise_error').css('display','block');
    else if(exercise.length < 2)
        $('#exercise_error').css('display','block').css('Exercise details should be more than 2 characters.');

    if(smoking_status != '' && alcohol_status != '' && exercise != '' && exercise.length >= 2)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : "post",
            url : registerUser,
            data : {'smoking_status' : smoking_status,'alcohol_status' : alcohol_status, 'exercise' : exercise,'first_name' : first_name,'last_name': last_name,'email' : email,'mobile': mobile,'gender' : gender,'age' : age, 'city' : city ,'address': address ,'name' : name,'contact_relation' : contact_relation,'contact_no' : contact_no, 'past_illness' : past_illness, 'chronic_condition' : chronic_condition, 'surgeries' : surgeries,'allergies' : allergies,'medication' : medication ,'isPatients' : 1},
            beforeSend: function()
            {
                $('#submit').attr('disabled',true);
            },
            success : function (response){
                var data = JSON.parse(response)
    
                if(data.status == 'success'){
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        timer: 5000
                    });

                    setTimeout(function(){
                        window.location.href = loginUrl;
                    },4000);
                }else{    
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        timer: 5000
                    });
                }
            },
            error: function(response)
            {
                if(response.status === 422)
                {
                    var errors = response.responseJSON;
                    Swal.fire({
                        title: "Error",
                        text: errors.message,
                        icon: "error",
                        timer: 5000
                    });
                }
            },
            complete: function (){
                $('#submit').attr('disabled',false);
            }
        });
    }
})

function getStateList(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getStates,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#state').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name));
            });
        }
    })
}

function getCityList(state_ID){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getCity,
        data : {'state_Id' : state_ID},
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#city').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name));
            });
        }
    })
}

function getGenderList(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getGender,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#gender').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.gender));
            });
        }
    })
}

function getSmokingStatus(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : smokingStatus,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#smoking_status').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.statusName));
            });
        }
    })
}

function getAlcoholStatus(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : alcoholStatus,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#alcohol').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.statusName));
            });
        }
    })
}