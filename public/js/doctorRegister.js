getGenderList();
getStateList();
getSpecialtyList();

$(document).on('change', '#state',function(){
    let state_ID = $(this).val();
    getCityList(state_ID); 
});

$(document).on('click','#submitForm', function(){
    let first_name = $('#first_name').val();
    let last_name = $('#last_name').val();
    let email = $('#email').val();
    let mobile_no = $('#mobile').val();
    let gender = $('#gender').val();
    let speciality = $('#speciality').val();
    let age = $('#age').val();
    let state = $('#state').val();
    let city = $('#city').val();
    let licenseNumber = $('#licenseNumber').val();

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : 'post',
        url : saveDoctorDetails,
        data : {'first_name' : first_name, 'last_name' : last_name, 'email' : email, 'mobile' : mobile_no, 'gender' : gender, 'speacility' : speciality, 'age' : age,'state' : state, 'city' : city, 'licenseNumber' : licenseNumber,'isPatients' : 0},
        success: function(response)
        {
            let data = JSON.parse(response);
            
            if(data.status == 'success'){
                Swal.fire({
                    title: "Success",
                    text: data.message,
                    icon: "success",
                    timer: 4000
                });

                setTimeout(function(){
                    window.location.reload();
                },4000);
            }else{    
                Swal.fire({
                    title: "Success",
                    text: data.message,
                    icon: "success",
                    timer: 4000
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
                    timer: 4000
                });
            }
        },
    })
})


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

function getSpecialtyList(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getSpecialty,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                $('#speciality').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.specialtyName));
            });
        }
    })
}
