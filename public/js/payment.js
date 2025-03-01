$(document).on('click','.payment', function(){
    $('.payment').attr('disabled',true);
    const appointment_id = $(this).data('id');

    $('#loader').css('display','block');

    fetchAppointmentDetails(appointment_id);
});

function razorPay(order)
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
                appointment_id: document.getElementById('appointment_id').value, // Retrieve appointment ID
                currency: order.currency,
                amount: order.amount
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
                    let successUrl = `${successPage}?appointment_id=${document.getElementById('appointment_id').value}`;
                    window.location.href = successUrl; // Redirect to success page
                } else {
                    Swal.fire('Payment verification failed.'); // Notify if verification fails
                }
            });
        },
        prefill: {
            name: "John Doe",
            email: "johndoe@yopmail.com",
            contact: "9857551454",
        },
        theme: {
            color: "#3399cc"
        }
    };

    const rzp = new Razorpay(options);
    document.getElementById('rzp-button').onclick = function (e) {
        rzp.open();
        e.preventDefault();
    };
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
                razorPay(response.paymentsData);
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
            const details = `
                <ul>
                    <li><strong>Payment ID:</strong> ${response.res_payment_id}</li>
                    <li><strong>Order ID:</strong> ${response.order_id}</li>
                    <li><strong>Amount:</strong> ₹ ${response.amount}.00</li>
                    <li><strong>Status:</strong> ${response.status}</li>
                    <li><strong>Transaction Date:</strong> ${response.formatted_date}</li>
                </ul>`;

            $('#paymentSummaryModal .modal-body').html(details);
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