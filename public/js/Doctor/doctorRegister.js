const genderId = $('#hidden_gender_ID').val();
const state_Id = $('#hidden_state_ID').val();
const specialty_Id = $('#hidden_specialty_ID').val();
const city_Id = $('#hidden_city_ID').val();

getGenderList(genderId);
getStateList(state_Id);
getSpecialtyList(specialty_Id);

if(state_Id != null && city_Id != null)
{
    getCityList(state_Id,city_Id); 
}

$(document).on('change', '#state',function(){
    let state_ID = $(this).val();
    getCityList(state_ID,city_Id); 
});

$('#doctorForm').on('submit', function(e){
    e.preventDefault();
    const email = $('#email').val();

    var formData = new FormData(this);
    formData.append('email',email);

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : 'post',
        url : saveDoctorDetails,
        data : formData,
        processData : false,
        contentType : false,
        beforeSend : function(){
            $('#submitForm').attr('disabled',true);
        },
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

                if(data.url === '')
                {
                    setTimeout(function(){
                        window.location.reload();
                    },4000);
                }else
                {
                    setTimeout(function(){
                        window.location.href = getDoctorList;
                    },4000);
                }
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
        complete : function(){
            $('#submitForm').attr('disabled',false);
        }
    })
})

// $(document).on('click','#submitForm', function(){
//     let first_name = $('#first_name').val();
//     let last_name = $('#last_name').val();
//     let email = $('#email').val();
//     let mobile_no = $('#mobile').val();
//     let gender = $('#gender').val();
//     let speciality = $('#speciality').val();
//     let age = $('#age').val();
//     let state = $('#state').val();
//     let city = $('#city').val();
//     let licenseNumber = $('#licenseNumber').val();

//     $.ajaxSetup({
//         headers:{
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     })
//     $.ajax({
//         type : 'post',
//         url : saveDoctorDetails,
//         data : {'first_name' : first_name, 'last_name' : last_name, 'email' : email, 'mobile' : mobile_no, 'gender' : gender, 'speacility' : speciality, 'age' : age,'state' : state, 'city' : city, 'licenseNumber' : licenseNumber,'isPatients' : 0},
//         success: function(response)
//         {
//             let data = JSON.parse(response);
            
//             if(data.status == 'success'){
//                 Swal.fire({
//                     title: "Success",
//                     text: data.message,
//                     icon: "success",
//                     timer: 4000
//                 });

//                 setTimeout(function(){
//                     window.location.href = getDoctorList;
//                 },4000);
//             }else{    
//                 Swal.fire({
//                     title: "Success",
//                     text: data.message,
//                     icon: "success",
//                     timer: 4000
//                 });
//             }
//         },
//         error: function(response)
//         {
//             if(response.status === 422)
//             {
//                 var errors = response.responseJSON;
//                 Swal.fire({
//                     title: "Error",
//                     text: errors.message,
//                     icon: "error",
//                     timer: 4000
//                 });
//             }
//         },
//     })
// })

$('input[type=radio]').on('change',function(){
    if($(this).val() === 'Yes')
    {
        $('#fileUploadDiv').css('display','block');
    }else{
        $('#fileUploadDiv').css('display','none');
    }
});

function getGenderList(selectedGenderId = null){
    var selectedTrue = false;

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
                if(response[key].id ==  selectedGenderId)
                    selectedTrue = true;
                else
                    selectedTrue = false;

                $('#gender').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.gender)
                .prop('selected',selectedTrue));
            });
        }
    })
}

function getStateList(selectedStateID = null){
    var selectedTrue = false;

    $('#state').append($("<option value='' selected disabled>Select State</option>"));

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
                if(response[key].id ==selectedStateID)
                    selectedTrue = true;
                else
                    selectedTrue = false;

                $('#state').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name)
                .prop('selected', selectedTrue));
            });
        }
    });

    $('#state').select2();
}
$(document).on('click', '.img-thumbnail', function () {
    const imgSrc = $(this).attr('src');
    $('#viewDoctorPictureModal img').attr('src', imgSrc);
});

function getCityList(state_ID,selectedCity_ID = null){
    var selectedTrue = false;
    $('#city').empty();

    $('#city').append($("<option value='' selected disabled>Select City</option>"));

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
                if(response[key].id == selectedCity_ID)
                    selectedTrue = true;
                else
                    selectedTrue = false;

                $('#city').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name)
                .prop('selected',selectedTrue));
            });
        }
    });

    $('#city').select2();
}

function getSpecialtyList(selectedSpecialty_ID = null){
    var selectedTrue = false;

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
                if(response[key].id == selectedSpecialty_ID)
                    selectedTrue = true;
                else    
                    selectedTrue = false;

                $('#speciality').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.specialtyName)
                .prop('selected',selectedTrue));
            });
        }
    })
}
