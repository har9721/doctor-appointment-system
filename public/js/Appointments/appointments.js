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

});

// $('#search').on('click',function(){
//     getAppointmentsList();
// });

// function getAppointmentsList()
// {
//     $.ajaxSetup({
//         headers:{
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     })
//     $.ajax({
//         type: "GET",
//         url: getAppointments,
//         data: {'from_date' : $('#from_date').val(), 'to_date' : $('#to_date').val(),'status': $('#status').val()},
//         success: function (response) {
            
//             $('#searchResults').empty();

//             if(response.length > 0)
//             {
//                 response.forEach(element => 
//                 {
//                     let doctor_name = element.doctor_full_name;
//                     let patients_name = element.patient_full_name;
//                     let speciality = element.specialtyName;
//                     let status = element.status;
//                     let appointment_date = element.appointmentDate;
//                     let appointment_time = element.time;
//                     let button = (element.status == 'pending' || element.status == 'confirmed') ? 'Cancel Appointment' : 'Rebook Appointment';
                        
//                     let appointmentCard = `
//                         <div class="card appointment-card mx-2">
//                             <div class="card-header">
//                                 <h5 class="mb-0">Dr. ${doctor_name}</h5>
//                             </div>
//                             <div class="card-body">
//                                 <p><strong>Specialty:</strong> ${speciality}</p>
//                                 <p><strong>Date:</strong> ${appointment_date}</p>
//                                 <p><strong>Time:</strong> ${appointment_time}</p>
//                                 <p><strong>Status:</strong> 
//                                     <span class="appointment-status status-pending">${status}</span>
//                                 </p>
//                                 <div class="text-right">
//                                     <button class="btn btn-danger">${button}</button>
//                                 </div>
//                             </div>
//                         </div>
//                     `;

//                     $('#searchResults').append(appointmentCard);
//                 });
//             }else{
//                 let msg = 'No record found';

//                 $('#searchResults').append(msg);
//             }
//         }
//     });
// }

$(document).on('click','.appointmentButoon', function()
{
    const appointment_id = $(this).data('id');
    const appointment_status = $(this).data('status');
    const appointment_date = $(this).data('date');

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "post",
        url : mark_appointments,
        data : {'appointment_id' : appointment_id, 'status' : appointment_status,'appointment_date' : appointment_date},
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

$(document).on('click','.rescheduleAppointment', function()
{
    const appointment_id = $(this).data('id');
    const date = $(this).data('date');

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "get",
        url : fetch_appointments_details,
        data : {'appointment_id' : appointment_id,'appointment_date' : date},
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

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : "get",
        url : fetch_doctor_time_slot,
        data : {'appointment_date' : date,'doctor_ID' : doctor_ID},
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