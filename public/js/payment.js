let localPaymentData = null;
let localIsAdvance = 0;

$(document).on('click','.payment', function(){
    $('.payment').attr('disabled',true);
    const appointment_id = $(this).data('id');

    $('#loader').css('display','block');

    fetchAppointmentDetails(appointment_id);
});

function razorPay(order, isAdvance)
{
    let appointment_id = document.getElementById('appointment_id').value;
    let payment_details_id = order.paymentDetails_ID || null;
    let rzp = null;

    const options = {   
        key: razorpayKey, // Razorpay API Key
        amount: order.response.amount, // Amount in paise
        currency: order.response.currency,
        name: "Doctor Appointment Fees",
        description:  `Payment for Order # ${order.response.id}_${appointment_id}`,
        order_id: order.response.id,
        callback_url: successRoute,
        customer_id: order.customer,
        "handler": function (response) {
            // Handle the response after successful payment
            var data = {
                razorpay_payment_id: response.razorpay_payment_id, // Payment ID
                razorpay_order_id: response.razorpay_order_id, // Order ID
                razorpay_signature: response.razorpay_signature, // Payment signature
                appointment_id: appointment_id, // Retrieve appointment ID
                currency: order.response.currency,
                amount: response.amount,
                payment_details_id: payment_details_id,
                name : order.userName || null,
                email : order.userEmail || null,
                contact : order.contact || null,
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
                    let successUrl = `${successPage}?appointment_id=${appointment_id}&payment_id=${payment_details_id}`;
                    window.location.href = successUrl; // Redirect to success page
                } else {
                    Swal.fire('Payment verification failed.'); // Notify if verification fails
                }
            });
        },
        modal: {
            ondismiss: function () {
                rzp.close(); 
            }
        },
        prefill: {
            name: order.userName,
            email: order.userEmail,
            contact: order.contact,
        },
        theme: {
            color: "#3399cc"
        }
    };

    rzp = new Razorpay(options);
    rzp.open();
}

function fetchAppointmentDetails(appointment_id)
{
    $.ajax({
        type : 'get',
        url : getAppointmentDetails,
        data : {appointment_id:appointment_id},
        success: function(response)
        {
            $('#doctor_name').val(response.getApointmentDetails.doctor_time_slot.doctor.user.full_name);
            let date_time = `${response.getApointmentDetails.appointmentDate} , ${response.getApointmentDetails.doctor_time_slot.time}`;
            $('#appointment_Date').val(date_time);
            $('#amount').val(response.getApointmentDetails.amount);
            $('#makePaymentModal').modal('show');
            $('#appointment_id').val(appointment_id);
            $('#amount_hidden').val(response.getApointmentDetails.amount);

            if(response.paymentsData)
            {
                // razorPay(response.paymentsData,response.isAdvance);
                localPaymentData = response.paymentsData;
                localIsAdvance = response.isAdvance;
            }
        },
        complete: function(){
            $('.payment').attr('disabled',false);
            $('#loader').css('display','none');
        }
    });
}

$(document).on('click','.payment_summary', function(){
    const appointment_id = $(this).data('id');
    $('#loader').css('display','block');

    $.ajax({
        type : 'get',
        url : fetchPaymentSummary,
        data : {appointment_id:appointment_id},
        success: function(response)
        {
            if(response)
            {
                let allDetails = '';
                const entries = Object.entries(response);
                
                entries.forEach(function([key, value]) {
                    console.log(key + ': ' + value);
                    
                    // Determine heading based on payment_type
                    let heading = '';
                    if(value.payment_type === 'advance') {
                        heading = '<h5><strong>Advance Payment Details</strong></h5>';
                    } else if(value.payment_type === 'full_payment') {
                        heading = '<h5><strong>Remaining Payment Details</strong></h5>';
                    }
                    
                    const details = `
                        <fieldset class="border border-warning p-3 rounded mb-4">
                            <legend class="float-none w-auto px-3 text-success">${heading}</legend>
                            <ul>
                                <li><strong>Payment ID:</strong> ${value.res_payment_id}</li>
                                <li><strong>Order ID:</strong> ${value.order_id}</li>
                                <li><strong>Amount:</strong> ₹ ${value.amount}</li>
                                <li><strong>Status:</strong> ${value.status}</li>
                                <li><strong>Transaction Date:</strong> ${value.formatted_date}</li>
                            </ul>
                        </fieldset>`;
                    
                    allDetails += details;
                });
        
                $('#paymentSummaryModal .modal-body').html(allDetails);
            }
            $('#paymentSummaryModal').modal('show');
        },
        error: function () {
            $('#paymentSummaryModal .modal-body').html('<p>Unable to fetch details. Please try again later.</p>');
            $('#paymentSummaryModal').modal('show');
        },
        complete: function(){
            $('.payment_summary').attr('disabled',false);
            $('#loader').css('display','none');
        }
    });
});

$(document).on('click','.prescription_summary', function(){
    const prescription_id = $(this).data('prescriptions_id');
    $('#loader').css('display','block');

    $('#prescriptions_details tbody.details').empty();

    $.ajax({
        type : 'get',
        url : fetchPrescriptionsDetails,
        data : {prescription_id:prescription_id},
        success: function(response)
        {
            let details;
            let i = 1;
            response.medicines.forEach(medicine => {
                details = `
                    <tr>
                        <td>${i}</td>
                        <td>${medicine.medicine}</td>
                        <td>${medicine.dosage}</td>
                        <td>${medicine.instruction}</td>
                    </tr>
                `;
                i++;

                $('#prescriptions_details tbody.details').append(details);
            });

            $('#additional_instructions').text(response.instructions  ? response.instructions : 'No additional instructions provided.');

            $('#prescriptionSummaryModal').modal('show');
        },
        error: function () {
            $('#prescriptionSummaryModal .modal-body').html('<p>Unable to fetch details. Please try again later.</p>');
            $('#prescriptionSummaryModal').modal('show');
        },
        complete: function(){
            $('.prescription_summary').attr('disabled',false);
            $('#loader').css('display','none');
        }
    });
});

$(document).on('click','#rzp-button', function(){
    razorPay(localPaymentData,localIsAdvance);
});