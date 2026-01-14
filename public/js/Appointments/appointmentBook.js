document.addEventListener('DOMContentLoaded', function() {
    //start date datepicker
    $('#date').datetimepicker({
        format:"d-m-Y",
        timepicker: false,
        datepicker : true,
        changeMonth:true,
        changeYear:true,
        minDate : "-1",
        scrollInput : false,
    });

    getCityList();
    getSpecialtyList();
    getPatientsList();

    let selectedTimeSlot = null;
    let selectedTimeSlotText = null;
    let advanceFees = 0.00;
    let paymentMethod = null;
    let consultationFees = 0.00;
    let followUpFees = 0.00;
    let selectedAppointmentDate = null;

    $('#search').on('click', function(){
        let speciality = $('#speciality').val();
        let city = $('#city').find(':selected').val();
        let date = $('#date').val();
        selectedAppointmentDate = date;

        if(speciality != '' || city != '' || date != '')
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                type : 'get',
                url : searchDoctor,
                data : {'speciality' : speciality,'date' : date, 'city' : city},
                success: function(response)
                {
                    // make empty div before append
                    $('#searchResults').empty();

                    if(response.length != 0)
                    {
                        response.forEach(doctor => {
                            const timeSlot = doctor.time_slot;
                            advanceFees = doctor.advanceFees;
                            paymentMethod = doctor.payment_mode;
                            consultationFees = doctor.consultationFees;
                            followUpFees = doctor.followUpFees;

                            if(timeSlot.length != 0)
                            {
                                const baseUrl = window.location.origin;
                                const fileName = doctor.fileName;
    
                                // Access the image URL
                                const imageUrl = baseUrl + '/storage/doctorProfilePictures/'+fileName;
    
                                let doctorCard = `
                                <div class="col-md-6">
                                    <div class="doctor-card">
                                        <img src="${imageUrl}" alt="${doctor.first_name}">
                                        <div class="doctor-info">
                                            <h4 style="color:black">Dr.${doctor.first_name}</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><label><b>Speciality :</b></label> <span>${doctor.specialtyName}</span></p>
                                                    <p><label><b>Consultation Fees :</b></label> <span>${consultationFees}</span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><label><b>Gender :</b></label> <span>${doctor.gender}</span></p>
                                                    <p><label><b>Advance Fees :</b></label> <span>${doctor.advanceFees}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion" id="accordion${doctor.id}">
                                            <div class="card">
                                                <div class="card-header" id="heading${doctor.id}">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse${doctor.id}" aria-expanded="true" aria-controls="collapse${doctor.id}">
                                                            View Available Time Slots
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapse${doctor.id}" class="collapse" aria-labelledby="heading${doctor.id}" data-parent="#accordion${doctor.id}">
                                                    <div class="card-body accordion-body">
                                                        <div id="timeSlotsContainer${doctor.id}" class="time-slots-container">
                                                        </div>
                                                        <div class="text-center">
                                                            <button class="book-btn" data-doctor-id="${doctor.id}">Book Appointment</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
    
                                $('#searchResults').append(doctorCard);
    
                                // append time slot
                                getTimeSlot(doctor.id,timeSlot,advanceFees,paymentMethod,consultationFees, followUpFees, selectedAppointmentDate);
                            }
                        });
                        
                    }else{
                        return Swal.fire({
                            title: "error",
                            text: "No records found. Plese try again.",
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
        }else{
            Swal.fire({
                title: "Error",
                text: "Please select at least one field.",
                icon: "error",
                timer: 2000
            });
        }
    });
});

function getTimeSlot(doctorID,timeSlot,fees,payment_method, consultationFees, followUpFees, appointmentDate)
{
    selectedTimeSlot = null;
    selectedTimeSlotText = null;
    advanceFees = fees;

    paymentMethod = payment_method;
    consultationFees = consultationFees;
    followUpFees = followUpFees;

    timeSlot.forEach(element => {
        const slot = `<div class="time-slot mr-1" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}" id="time-slot-${element.id}" data-time_slot_text ="${element.time}" data-payment-mode="${paymentMethod}" data-consultation-fees="${consultationFees}" data-follow-up-fees="${followUpFees}" data-advance-fees="${advanceFees}" data-appointment-date="${element.availableDate}" data-selected-date="${appointmentDate}">${element.time}</div>`;

        $(`#timeSlotsContainer${doctorID}`).append(slot);

        if(element.isBooked == 1)
        {
            $(`#time-slot-${element.id}`).css('backgroundColor','yellow').css('color','black').css('cursor','not-allowed').attr('disabled',true).removeAttr('onclick');
        }
    });
}

function clickOnTimeSlot(timeSlot)
{
    selectedTimeSlot = $(timeSlot).data('time_slot_id');
    selectedTimeSlotText = $(timeSlot).data('time_slot_text');
    paymentMethod = $(timeSlot).data('payment-mode');
    consultationFees = $(timeSlot).data('consultation-fees');
    selectedAppointmentDate = $(timeSlot).data('selected-date');

    $('.time-slot').removeClass('selected');

    timeSlot.classList.add('selected');
}


$(document).on('click', '.book-btn',function(){

    if(selectedTimeSlot)
    {
        $('#selectedTimeSlot').html(`<strong>Time:</strong> ${selectedTimeSlotText}`);
        $('#advanceFees').html(`<strong>Advance Fees:</strong> ${advanceFees}`);
        $('#advanceFees').attr('data-payment-method', paymentMethod);
        $('#consult_fees').val(consultationFees);

        $('#available-date').html(`<strong>Date:</strong> ${selectedAppointmentDate}`);
        $('#consultationFeesInfo').html(`<strong>Consultation Fees:</strong> ${consultationFees}`);
    
        $('#bookModal').modal('show');

        // let time_slot = selectedTimeSlot;
        // let date = $('#date').val();
        // let patient_ID = $('#patients_ID').val();
        
        // $.ajaxSetup({
        //     headers:{
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // })
        // $.ajax({
        //     type : 'post',
        //     url : bookingUrl,
        //     data : {'timeSlot' : time_slot,'date' : date,'patient_ID' : patient_ID},
        //     success : function(response)
        //     {
        //         if(response['status'] == 'success'){
        //             Swal.fire({
        //                 title: "Success",
        //                 text: response['message'],
        //                 icon: "success",
        //                 timer: 3000
        //             });

        //             setTimeout(function(){
        //                 window.location.reload();
        //             },2000);
        //         }else{    
        //             Swal.fire({
        //                 title: "Error",
        //                 text: response['message'],
        //                 icon: "error",
        //                 timer: 3000
        //             });
        //         }
        //     },
        //     error: function(response)
        //     {
        //         if(response.status === 422)
        //         {
        //             var errors = response.responseJSON;
        //             Swal.fire({
        //                 title: "Error",
        //                 text: errors.message,
        //                 icon: "error",
        //                 timer: 5000
        //             });
        //         }
        //     },
        // })
        
    }else{
        return Swal.fire({
            title: "Error",
            text: "Please select a time slot.",
            icon: "error",
            timer: 5000
        });
    }
    
});

$(document).on('change','#speciality', function(){
    getDoctorList($(this).val());
});

$(document).on('click','#searchForTimeslot',function(){
    const doctor = $('#doctor').val();
    const date = $('#date').val();
    const patients = $('#patients').val();
    const speciality = $('#speciality').val();
    selectedTimeSlot = null;
    
    if(!Number(patients))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select patients name.',
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
        data : {'doctor_ID' : doctor, 'date' : date},
        success : function(response)
        {
            if(response)
            {
                if(response.time_slot)
                {
                    $(`#timeSlotDiv`).empty();

                    response.time_slot.forEach(element => {
                        if(element)
                        {
                            const available_time_slot = `<div class="time-slot mr-1" onclick="clickOnTimeSlot(this)" data-time_slot_id ="${element.id}">${element.time}</div>`;

                            $(`#timeSlotDiv`).append(available_time_slot);
                        }
                    });
                }
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
    const date = $('#date').val();
    const patients = $('#patients').val();
    const speciality = $('#speciality').val();

    if(!Number(patients))
    {
        return Swal.fire({
            title : 'error',
            text : 'Please select patients name.',
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

    if(selectedTimeSlot)
    {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            type : 'POST',
            url : bookingUrl,
            beforeSend : function(){ 
                $('#confirmAppointment').attr('disabled',true);
            },
            data : {'doctor_ID' : doctor, 'date' : date, 'patient_ID' : patients, 'timeSlot' : selectedTimeSlot},
            success : function(response)
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

function getCityList(){
    $('#city').empty();
    $("#city").append($("<option value='' disabled selected>Select City</option>"));

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
                $('#city').append($("<option></option>")
                .attr("value", response[key].id)
                .text(val.name));
            });
        }
    });

    $("#city").select2({
        placeholder: "Select city name",
    });	
}

function getSpecialtyList(){    
    $('#speciality').empty();
    $("#speciality").append($("<option value='' disabled selected>Select Speciality</option>"));

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

function getPatientsList(){
    $('#patients').empty();
    $("#patients").append($("<option value='' disabled selected>Select Patients</option>"));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : patientsList,
        success : function (response){
            $.each(response, function (key, val) 
            { 
                if(val.user)
                {
                    $('#patients').append($("<option></option>")
                    .attr("value", response[key].id)
                    .text(val.user.full_name));
                }
            });
        }
    });

    $("#patients").select2({
        placeholder: "Select patients name",
    });
}

function getDoctorList(speciality_id){
    $('#doctor').empty();
    $("#doctor").append($("<option value='' disabled selected>Select Doctor</option>"));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type : "get",
        url : doctorList,
        data : {'speciality_id' : speciality_id},
        success : function (response){
            $('#doctor').append(`<option value=""></option`);

            $.each(response, function (key, val) 
            { 
                if(val.user)
                {
                    $('#doctor').append($("<option></option>")
                    .attr("value", response[key].id)
                    .text(val.user.full_name));
                }
            });
        }
    });

    $("#doctor").select2({
        placeholder: "Select doctor name",
    });
}

$(document).on('click','#reasonModal', function(){
    
    const reason = $('#reason').val();
    bookAppointment(selectedTimeSlot, reason);
});

function bookAppointment(selectedTimeSlot, reason)
{
    bookingUrl = (paymentMethod && paymentMethod.toLowerCase() === 'none') ? bookingUrl : bookingWithPaymentGateway;

    if(selectedTimeSlot)
    {
        let date = $('#date').val();
        let patient_ID = $('#patients_ID').val();
        
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            type : 'post',
            url : bookingUrl,
            data : {
                'timeSlot' : selectedTimeSlot,'date' : date,'patient_ID' : patient_ID, 'reason' : reason, 'advanceFees' : advanceFees, 'consultationFees' : consultationFees
            },
            beforeSend : function(){ 
                $('#reasonModal').attr('disabled',true);
            },
            success : function(response)
            {
                if(response['status'] == 'success'){

                    if(paymentMethod && paymentMethod.toLowerCase() !== 'none' && response.paymentsData)
                    {
                        return razorPay(response.paymentsData, response.appointment_id,response.paymentDetails_id);
                    }

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
            complete :function ()
            {
                $('#reasonModal').attr('disabled',false);
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
        })
        
    }else{
        return Swal.fire({
            title: "Error",
            text: "Please select a time slot.",
            icon: "error",
            timer: 5000
        });
    }
}

function razorPay(order, appointment_id, paymentDetails_id)
{
    const options = {   
        key: razorpayKey, // Razorpay API Key
        amount: order.amount, // Amount in paise
        currency: order.currency,
        name: "Doctor Appointment Fees",
        description:  `Payment for Order # ${order.id}`,
        order_id: order.id,
        callback_url: successRoute,
        "handler": function (response) {
        
            // Handle the response after successful payment
            var data = {
                razorpay_payment_id: response.razorpay_payment_id, // Payment ID
                razorpay_order_id: response.razorpay_order_id, // Order ID
                razorpay_signature: response.razorpay_signature, // Payment signature
                appointment_id: appointment_id, // Retrieve appointment ID
                currency: order.currency,
                amount: order.amount,
                payment_details_id: paymentDetails_id,
                name : order.patientName,
                email : order.patientEmail,
                contact : order.patientContact
            };

            // Send payment details to the server for verification
            fetch(successUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(data) // Convert data to JSON
            }).then(response => response.json()).then(data => {

                if (data.success) {
                    Swal.fire('Payment successful!');
                    let successUrl = `${successPage}?appointment_id=${appointment_id}`;

                    window.location.href = successUrl; // Redirect to success page
                } else {
                    Swal.fire('Payment verification failed.'); // Notify if verification fails
                }
            });
        },
        prefill: {
            name: order.patientName,
            email: order.patientEmail,
            contact: order.patientContact,
        },
        theme: {
            color: "#14eb2a"
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}