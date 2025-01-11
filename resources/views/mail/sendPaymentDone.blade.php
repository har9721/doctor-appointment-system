<html>
<body>
    <h3>Dear {{ $patientName }},</h3>
    <p>We are pleased to inform you that your payment has been successfully processed. Below are the details of your transaction:</p>
    
    <h5>Appointment Details:</h5>
    <ul>
        <li>Doctor's Name: Dr. {{ $doctorName }}</li>
        <li>Appointment Date: {{ $date }}</li>
        <li>Appointment Time: {{ $time }}</li>
    </ul>

    <h5>Payment Details:</h5>
    <ul>
        <li>Transaction ID: {{ $transaction_id }}</li>
        <li>Payment Date: {{ $paymentDate }}</li>
        <li>Amount Paid <span>&#8377;</span>{{ $amount }}</li>
    </ul>
    
    <p>Thank you for completing the payment. Please keep this email for your records. If you have any questions or require further assistance, feel free to contact us.</p>

    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
