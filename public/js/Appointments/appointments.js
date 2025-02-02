$(document).ready(function () {
    //start date datepicker
    $('#from_date').datetimepicker({
        format:"d-m-Y",
        timepicker: false,
        datepicker : true,
        changeMonth:true,
        changeYear:true,
        // minDate : "-1",
        scrollInput : false,
    });

    //start date datepicker
    $('#to_date').datetimepicker({
        format:"d-m-Y",
        timepicker: false,
        datepicker : true,
        changeMonth:true,
        changeYear:true,
        minDate : "-1",
        scrollInput : false,
    });

    // appointment_date
    $('#appointment_date').datetimepicker({
        format:"d-m-Y",
        timepicker: false,
        datepicker : true,
        changeMonth:true,
        changeYear:true,
        minDate : "-1",
        scrollInput : false,
    });

    // appointment_date
    $('#appointment_date_for_edit').datetimepicker({
        format:"d-m-Y",
        timepicker: false,
        datepicker : true,
        changeMonth:true,
        changeYear:true,
        minDate : "-1",
        scrollInput : false,
    });

});

$(document).on('click','.appointmentButoon', function()
{
    const appointment_id = $(this).data('id');
    const appointment_status = $(this).data('status');
    const appointment_date = $(this).data('date');
    const patient_ID = $(this).data('patient_id');

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "post",
        url : mark_appointments,
        data : {'appointment_id' : appointment_id, 'status' : appointment_status,'appointment_date' : appointment_date, 'patient_ID' : patient_ID},
        beforeSend : function(){
            $('#confirm_button').attr('disabled',true)
            $('#cancel_button').attr('disabled',true)
        },
        success: function (response){
            if(response['status'] == 'success'){
                Swal.fire({
                    title: "Success",
                    text: response['message'],
                    icon: "success",
                    timer: 3000
                });

                setTimeout(function(){
                    window.location.reload();
                },2000);
            }else{    
                Swal.fire({
                    title: "Error",
                    text: response['message'],
                    icon: "error",
                    timer: 3000
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
            $('#confirm_button').attr('disabled',false);
            $('#cancel_button').attr('disabled',false);
        }
    })
});

$(document).on('click','.unComplete', function()
{
    const appointment_id = $(this).data('id');
    const appointment_status = $(this).data('status');
    const appointment_date = $(this).data('date');
    const patient_ID = $(this).data('patient_id');

    (async () => {
        const { value: reason } = await Swal.fire({
            title: "Reason",
            input: "text",
            inputLabel: "Your reason",
            inputPlaceholder: "Enter your reason",
            showCancelButton: true,
            inputValidator: (value) => {
                if (value === "") {
                    return "Kindly provide the reason for archieve the appointment.!";
                }else if(value.length <= 2)
                {
                    return "The reson must atleast 3 letee"
                }else
                {
                    $.ajaxSetup({
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    $.ajax({
                        type : "post",
                        url : mark_appointments,
                        data : {'appointment_id' : appointment_id, 'status' : appointment_status,'appointment_date' : appointment_date, 'patient_ID' : patient_ID, 'reason' : value},
                        beforeSend : function(){
                            $('#confirm_button').attr('disabled',true)
                            $('#cancel_button').attr('disabled',true)
                        },
                        success: function (response){
                            if(response['status'] == 'success'){
                                Swal.fire({
                                    title: "Success",
                                    text: response['message'],
                                    icon: "success",
                                    timer: 3000
                                });
                
                                setTimeout(function(){
                                    window.location.reload();
                                },2000);
                            }else{    
                                Swal.fire({
                                    title: "Error",
                                    text: response['message'],
                                    icon: "error",
                                    timer: 3000
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
                            $('#confirm_button').attr('disabled',false);
                            $('#cancel_button').attr('disabled',false);
                        }
                    })
                }
            }
        });
    })();
});

$(document).on('click','.rescheduleAppointment', function()
{
    const appointment_id = $(this).data('id');
    const date = $(this).data('date');
    const patient_ID = $(this).data('patient_id');

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "get",
        url : fetch_appointments_details,
        data : {'appointment_id' : appointment_id,'appointment_date' : date, 'patient_ID' : patient_ID},
        beforeSend : function()
        {
            $('#reshedule_button').attr('disbled',true);
        },
        success: function (response){

            $('#timeSlotDiv').empty();

            if(response != null)
            {
                $('#rescheduleModal').modal('show');

                $('#appointment_date').val(response.bookedSlot.appointmentDate);

                $('#hidden_appointment_date').val(response.bookedSlot.appointmentDate);
                $('#hidden_appointment_id').val(response.bookedSlot.id);
                $('#hidden_timeslot_id').val(response.bookedSlot.doctorTimeSlot_ID);

                response.availableTimeSlot.forEach(element => {
                    let selectedTimeSlot = null;

                    if(response.bookedSlot.doctor_time_slot != null)
                    {
                        $('#hidden_doctor_id').val(response.bookedSlot.doctor_time_slot.doctor_ID);
                        $('#hidden_patient_id').val(response.bookedSlot.patient_ID);

                        if(element.id === response.bookedSlot.doctor_time_slot.id)
                        {
                            var slot = `<div class="time-slot mr-1" style="background-color:black;color:white" title="booked slot" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${response.bookedSlot.doctor_time_slot.id}">${response.bookedSlot.doctor_time_slot.time}</div>`;
                        }else{
                            var slot = `<div class="time-slot mr-1" title="available time slot" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}">${element.time}</div>`;
                        }

                        $(`#timeSlotDiv`).append(slot);
                    }
                });

            }else{
                Swal.fire({
                    title: "Error",
                    text: "The record is not found.",
                    icon: "error",
                    timer: 3000
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
        complete: function()
        {
            $('#reshedule_button').attr('disabled',false);
        }
    })
});

$(document).off('change', '#appointment_date').on('change','#appointment_date', function(){
    const date = $(this).val();
    const doctor_ID = $('#hidden_doctor_id').val();
    const patient_ID = $('#hidden_patient_id').val();


    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "get",
        url : fetch_doctor_time_slot,
        data : {'appointment_date' : date,'doctor_ID' : doctor_ID,'patient_ID' : patient_ID},
        success: function (response){

            $('#timeSlotDiv').empty();

            if(response != null)
            {
                response.time_slot.forEach(element => {
                    const slot = `<div class="time-slot mr-1" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}">${element.time}</div>`;
    
                    $(`#timeSlotDiv`).append(slot);
                });
            }else{
                Swal.fire({
                    title: "Error",
                    text: "The record is not found.",
                    icon: "error",
                    timer: 3000
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
});

let selectedTimeSlot = null;

function clickOnTimeSlot(timeSlot)
{   
    selectedTimeSlot = $(timeSlot).data('time_slot_id');

    $('#submit').attr('disabled',false);

    $('.time-slot').removeClass('selected');

    timeSlot.classList.add('selected');
}

$(document).on('click','#submit', function(){   

    if(selectedTimeSlot)
    {
        let patient_ID = $('#hidden_patient_id').val();
        let doctor_ID = $('#hidden_doctor_id').val();
        let appointment_id = $('#hidden_appointment_id').val();
        let appointment_date = $('#appointment_date').val();
        let hidden_appointment_date = $('#hidden_appointment_date').val();
        let hidden_timeslot_id = $('#hidden_timeslot_id').val();

        if(appointment_date != hidden_appointment_date || hidden_timeslot_id != selectedTimeSlot)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                type : "get",
                url : reschedule_appointment,
                data : {'appointment_date' : appointment_date,'doctor_ID' : doctor_ID, 'appointment_id' : appointment_id, 'patient_ID' : patient_ID,'new_time_slot' : selectedTimeSlot},
                beforeSend : function()
                {
                    $('#submit').attr('disabled',true);
                },
                success: function (response)
                {
                    if(response['status'] == 'success'){
                        Swal.fire({
                            title: "Success",
                            text: response['message'],
                            icon: "success",
                            timer: 3000
                        });
        
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }else{    
                        Swal.fire({
                            title: "Error",
                            text: response['message'],
                            icon: "error",
                            timer: 3000
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
                complete : function()
                {
                    $('#submit').attr('disabled',false);
                }
            });
        }else{
            Swal.fire({
                title: "Error",
                text: "You have selected the same appointment date and time. Please select a different date or time to reschedule.",
                icon: "error",
                timer: 5000
            });
        }
    }else{
        Swal.fire({
            title: "Error",
            text: "Please select time slot for appointment.",
            icon: "error",
            timer: 4000
        });
    }
});

$(document).on('click','.amount', function(){
    const id = $(this).data('id');
    $('#add_amount_modal').modal('show');
    $('#appointment_id').val(id);
});

$(document).on('click','.addAmount', function(){
    const appointment_id = $('#appointment_id').val();
    const amount = $('#amount').val();

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "post",
        url : saveAmount,
        data : {'appointment_id' : appointment_id,'amount' : amount},
        beforeSend : function()
        {
            $('#addAmount').attr('disabled',true);
        },
        success: function (response)
        {
            let data = JSON.parse(response);

            if(data['status'] == 'success')
            {
                Swal.fire({
                    title: "Success",
                    text: data['message'],
                    icon: "success",
                    timer: 3000
                });

                setTimeout(function(){
                    window.location.reload();
                },1000);
            }else{    
                Swal.fire({
                    title: "Error",
                    text: data['message'],
                    icon: "error",
                    timer: 3000
                });
            }
        },
        error: function(data)
        {
            if(data.status === 422)
            {
                var errors = data.responseJSON;
                Swal.fire({
                    title: "Error",
                    text: errors.message,
                    icon: "error",
                    timer: 4000
                });
            }
        },
        complete : function()
        {
            $('#addAmount').attr('disabled',false);
        }
    });
});

$(document).on('click','.appointmentEditButton', function(){

    let appointment_id = $(this).data('id');
    let appointment_date = $(this).data('date');
    let speciality_id = '';
    let doctor_id = '';
    let city_id = '';
    let patient_ID = '';

    if(appointment_id)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : "get",
            url : fetch_appointments_details,
            data : {'appointment_id' : appointment_id, 'appointment_date' : appointment_date},
            success : function (response){
                $('#appointment_id').val(appointment_id);

                if(response)
                {
                    speciality_id = (response.bookedSlot.doctor_time_slot.doctor) ? response.bookedSlot.doctor_time_slot.doctor.specialty_ID : '';
                    
                    doctor_id = (response.bookedSlot.doctor_time_slot) ? response.bookedSlot.doctor_time_slot.doctor_ID : '';                    

                    city_id = (response.bookedSlot.doctor_time_slot.doctor.user) ? response.bookedSlot.doctor_time_slot.doctor.user.city_ID : '';

                    $('#appointment_date_for_edit').val(response.bookedSlot.appointmentDate);
                    
                    patient_ID = (response.bookedSlot) ? response.bookedSlot.patient_ID : '';

                    $('#patient_ID').val(patient_ID);

                    if(response.availableTimeSlot)
                    {
                        $(`#timeSlotDivForEdit`).empty();

                        response.availableTimeSlot.forEach(element => {
                            
                            if(element)
                            {
                                // if(element.id == response.bookedSlot.doctorTimeSlot_ID)
                                // {
                                //     element.classList.add('selected');
                                // }

                                const available_time_slot = `<div class="time-slot mr-1" style="background-color=black" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}">${element.time}</div>`;

                                $(`#timeSlotDivForEdit`).append(available_time_slot);
                            }
                        });
                    }
                }
            },
            complete :  function()
            {
                getSpecialtyList(speciality_id);
                getCityList(city_id);
                getDoctorList(speciality_id,city_id,doctor_id);

                $('#edit_appointment').modal('show');
            }
        });
    }
});

function getCityList(id){
    $('#city').empty();
    $("#city").append($("<option value='' disabled selected>Select City</option>"));
    let selectedTrue = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : getCity,
        data : {'state_Id' : ''},
        success : function (response){
            $.each(response, function (key, val) 
            { 
                if(response[key].id == id)
                {
                    selectedTrue = true;
                }else{
                    selectedTrue = false;
                }

                $('#city').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name)
                .prop('selected',selectedTrue));
            });
        }
    });

    $("#city").select2({
        placeholder: "Select city name",
        dropdownParent: $("#edit_appointment")
    });	
}

function getSpecialtyList(id = null){
    
    $('#speciality').empty();
    $("#speciality").append($("<option value='' disabled selected>Select Speciality</option>"));
    let selectedTrue = false;

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
                if(response[key].id == id)
                {
                    selectedTrue = true;
                }else{
                    selectedTrue = false;
                }

                $('#speciality').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.specialtyName)
                .prop('selected',selectedTrue));
            });
        }
    });
}

$(document).on('change','#city', function(){
    getSpecialtyList();
    $('#doctor').empty();
});

$(document).on('change','#speciality', function(){
    let speciality_id = $(this).val();
    let city_ID = $('#city').val();

    if(speciality_id)
    {
        getDoctorList(speciality_id,city_ID,null);
    }
});

function getDoctorList(speciality_id,city_ID,id){
    $('#doctor').empty();
    $("#doctor").append($("<option value='' disabled selected>Select Doctor</option>"));
    let selectedTrue = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : doctorList,
        data : {'speciality_id' : speciality_id,'city_ID' : city_ID},
        success : function (response){
            $('#doctor').append(`<option value=""></option`);
            $.each(response, function (key, val) 
            { 
                if(response[key].id == id)
                {
                    selectedTrue = true;
                }else{
                    selectedTrue = false;
                }

                if(val.user != null)
                {
                    $('#doctor').append($("<option></option>")
                        .attr("value", response[key].id)
                        .text(val.user.full_name)
                        .prop('selected',selectedTrue)
                    );
                }
            });
        }
    });

    $("#doctor").select2({
        placeholder: "Select doctor name",
        dropdownParent: $("#edit_appointment")
    });
}

function clickOnTimeSlot(timeSlot)
{
    selectedTimeSlot = $(timeSlot).data('time_slot_id');

    $('.time-slot').removeClass('selected');

    timeSlot.classList.add('selected');
}

$(document).off('click', '#searchForTimeslot').on('click','#searchForTimeslot',function(){
    const doctor = $('#doctor').val();
    const date = $('#appointment_date_for_edit').val();
    const city = $('#city').val();
    const speciality = $('#speciality').val();
    selectedTimeSlot = null;
    
    if(!Number(city))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select city name.',
            icon : 'error',
            timer : 3000
        });
    }

    if(!Number(speciality))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select specialty.',
            icon : 'error',
            timer : 3000
        });
    }

    if(!Number(doctor))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select doctor.',
            icon : 'error',
            timer : 3000
        });
    }

    if(date == '')
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select a date',
            icon : 'error',
            timer : 3000
        });
    }

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.ajax({
        type : 'GET',
        url : getAvailableTimeSlot,
        data : {'doctor_ID' : doctor, 'date' : date, 'city_id' : city, 'speciality_ID' : speciality},
        success : function(response)
        {
            if(Object.keys(response).length !== 0)
            {
                $(`#timeSlotDivForEdit`).empty();

                if(Object.keys(response.time_slot).length !== 0)
                {                    
                    response.time_slot.forEach(element => {
                        if(element)
                        {
                            const available_time_slot = `<div class="time-slot mr-1" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}">${element.time}</div>`;
    
                            $(`#timeSlotDivForEdit`).append(available_time_slot);
                        }
                    });
                }else{
                    const text = `<div class="mr-1" style="text-align:center"><h4>No slot available....</h4></div>`;
    
                    $(`#timeSlotDivForEdit`).append(text);
                }
            }else{
                $(`#timeSlotDivForEdit`).empty();

                const text = `<div class="mr-1" style="text-align:center"><h4>No slot available....</h4></div>`;

                $(`#timeSlotDivForEdit`).append(text);
            }
        },
        complete:function ()
        {
            $('#confirmAppointment').css('visibility','visible');
        }
    });
});

$(document).on('click','#confirmAppointment', function(){
    const doctor = $('#doctor').val();
    const appointment_id = $('#appointment_id').val();
    const date = $('#appointment_date_for_edit').val();
    const speciality = $('#speciality').val();
    const patient_ID = $('#patient_ID').val();

    if(!Number(speciality))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select specialty.',
            icon : 'error',
            timer : 3000
        });
    }

    if(!Number(doctor))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select doctor.',
            icon : 'error',
            timer : 3000
        });
    }

    if(date == '')
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select a date',
            icon : 'error',
            timer : 3000
        });
    }

    if(selectedTimeSlot)
    {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            type : 'POST',
            url : updateAppointments,
            beforeSend : function(){ 
                $('#confirmAppointment').attr('disabled',true);
            },
            data : {
                'doctor_ID' : doctor, 'date' : date, 'appointment_id' : appointment_id, 'timeSlot' : selectedTimeSlot, 'patient_ID' : patient_ID
            },
            success : function(response)
            {
                var data = JSON.parse(response)
                
                if(data.status == 'success'){
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success",
                        timer: 3000
                    });
    
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }else{    
                    Swal.fire({
                        title: "Error",
                        text: data.message,
                        icon: "error",
                        timer: 3000
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
            complete:function ()
            {
                $('#confirmAppointment').attr('disabled',false);
            }
        });

    }else{
        return Swal.fire({
            title : 'error',
            text : 'Please select time slot',
            icon : 'error',
            timer : 3000
        });
    }
});