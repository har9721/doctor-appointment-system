document.addEventListener('DOMContentLoaded', function() {
    let selectedDoctorId = document.getElementById('doctorSelect');

    var calendarEl = document.getElementById('calendar');

    selectedDoctorId = String(selectedDoctorId);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        timeZone: 'local',
        height: 800,
        contentHeight: 750,
        aspectRatio: 3,
        slotMinTime: '10:00:00',
        slotMaxTime: '19:00:00',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },    

        events: function(fetchInfo, successCallback, failureCallback) {

            if (!Number(selectedDoctorId)) {
                successCallback([]); // no doctor selected
                return false;
            }

            $.ajax({
                url: getDoctorTimeSlot,
                type: "GET",
                data: {
                    doctor_id: selectedDoctorId,
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr
                },
                success: function(response) {

                    if(response.length === 0)
                    {
                        Swal.fire({
                            title: "Not Allowed",
                            text: "No timeslots available",
                            icon: "warning",
                            timer: 3000
                        });

                        return false;
                    }
                    successCallback(response);
                },
                error: function() {
                    failureCallback();
                }
            });
        },
        eventRender: function(event, element, view) {
            
        },
        dateClick: function(info) {            

        },

        editable : true,
        selectable : true,

        eventClick : function(event)
        {
            const today = new Date();
            const startTime = event.event.extendedProps.start_time;
            let appointmentdate = event.event.extendedProps.appointmentdate;
            const finalDate = appointmentdate+' '+startTime;
            const clickedDate = parseDateTime(finalDate);
            const isBooked = event.event.extendedProps.isBooked;

            clickedDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);

            if (clickedDate < today) {
                Swal.fire({
                    title: "Not Allowed",
                    text: "You cannot select past dates.",
                    icon: "warning",
                    timer: 3000
                });
                return false;
            }
            if (isBooked == 1) {
                Swal.fire({
                    title: "Not Allowed",
                    text: "This slot is already booked by another patients.",
                    icon: "warning",
                    timer: 3000
                });
                return false;
            }
            
            let timeSlotId = event.event.id;
            let selectedTime = event.event.extendedProps.time;
            let consultationFees = event.event.extendedProps.doctor.consultationFees;
            let advanceFees = event.event.extendedProps.doctor.advanceFees;
            let paymentMethod = event.event.extendedProps.doctor.payment_mode;
            
            $('#available-date').html(`<strong>Date:</strong> ${appointmentdate}`);
            $('#appointment_date').val(appointmentdate);
            $('#selectedTimeSlot').html(`<strong>Time:</strong> ${selectedTime}`);
            $('#selectedTimeSlot').val(selectedTime);
            $('#slot_id').val(timeSlotId);
            $('#consultationFeesInfo').html(`<strong>Consultation Fees:</strong> ${consultationFees}`);
            $('#consultation_fees').val(consultationFees);
            $('#advanceFees').html(`<strong>Advance Fees:</strong> ${advanceFees}`);
            $('#advanceFees').attr('data-payment-method', paymentMethod);
            $('#advanceFees').val(advanceFees);

            $('#bookModal').modal('show');
        },

        eventDrop : function(info)
        {
              
        },

        eventRemove : function()
        {
            console.log('remove');
        },
        eventContent: function (event) {
            let status = event.event.extendedProps.status;
            let time = event.event.extendedProps.time;
            let isBooked = event.event.extendedProps.isBooked;
            let isBookedText = (isBooked) ? ' Booked' : '';

            let customTitle = document.createElement("div");
            customTitle.innerHTML = `<b>${time}</b> <br> ${status} &nbsp; ${isBookedText}`;

            return { domNodes: [customTitle] };
        },
        eventDidMount: function (info) {
            let status = info.event.extendedProps.status;
            let isBooked = info.event.extendedProps.isBooked;

            if (status === 'Available') {
                info.el.style.backgroundColor = '#4e73df'; 
                info.el.style.color = 'white'; 
            } else {
                info.el.style.backgroundColor = '#e11509';
                info.el.style.color = 'white';
            }

            if (isBooked) {
                info.el.style.backgroundColor = '#0be53eff'; 
                info.el.style.color = 'black'; 
            }
        }
    });

    calendar.render();

    $('#doctorSelect').on('change', function () {
        selectedDoctorId = $(this).val();
        calendar.refetchEvents();
    });
});

$(document).on('click','#reasonModal', function(){
    
    const reason = $('#reason').val();
    let slotId = $('#slot_id').val();
    let appointmentDate = $('#appointment_date').val();
    let consultationFees = $('#consultation_fees').val();
    let advanceFees = $('#advanceFees').val();
    let paymentMethod = $('#advanceFees').data('payment-method');
    let patient_ID = $('#patient_id').val();

    bookingUrl = (paymentMethod && paymentMethod.toLowerCase() === 'none') ? bookingUrl : bookingWithPaymentGateway;

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type : 'post',
        url : bookingUrl,
        data : {
            'timeSlot' : slotId,'date' : appointmentDate, 'reason' : reason, 'advanceFees' : advanceFees, 'consultationFees' : consultationFees, 'patient_ID': patient_ID
        },
        beforeSend : function(){ 
            $('#reasonModal').attr('disabled',true);
        },
        success : function(response)
        {
            if(response['status'] == 'success'){

                if(paymentMethod && paymentMethod.toLowerCase() !== 'none' && response.paymentsData)
                {
                    return razorPay(response.paymentsData, response.appointment_id,response.paymentDetails_id,response.isAdvance);
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
});

function razorPay(order, appointment_id, paymentDetails_id, isAdvance)
{
    const options = {   
        key: razorpayKey, // Razorpay API Key
        amount: order.response.amount, // Amount in paise
        currency: order.response.currency,
        name: "Doctor Appointment Fees",
        description:  `Payment for Order # ${order.response.id}`,
        order_id: order.response.id,
        callback_url: successRoute,
        "handler": function (response) {
        
            // Handle the response after successful payment
            var data = {
                razorpay_payment_id: response.razorpay_payment_id || '', // Payment ID
                razorpay_order_id: response.razorpay_order_id || order.response.id,
                razorpay_signature: response.razorpay_signature || '', // Payment signature
                appointment_id: appointment_id, // Retrieve appointment ID
                currency: order.response.currency,
                amount: order.response.amount,
                payment_details_id: paymentDetails_id,
                name : order.userName,
                email : order.userEmail,
                contact : order.contact,
                isAdvance : isAdvance || 0
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
                    let successUrl = `${successPage}?appointment_id=${appointment_id}&payment_id=${paymentDetails_id}`;

                    window.location.href = successUrl; // Redirect to success page
                } else {
                    Swal.fire('Payment verification failed.'); // Notify if verification fails
                }
            });
        },
        prefill: {
            name: order.userName,
            email: order.userEmail,
            contact: order.contact,
        },
        theme: {
            color: "#14eb2a"
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}

function parseDateTime(dateTimeStr) {
    const [date, time] = dateTimeStr.split(' ');
    const [day, month, year] = date.split('-');

    return new Date(`${year}-${month}-${day}T${time}`);
}


