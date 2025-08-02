<html>
<body>
    <h3>Dear {{ $patientName }},</h3>
    <p>We are pleased to inform you that your payment has been successfully processed. Below are the details of your transaction:</p>
    
    <h5>Appointment Details:</h5>
    <ul>
        <li>Appointment Number : {{ $appointmentNo }} </li>
        <li>Doctor Name: Dr. {{ $doctorName }}</li>
        <li>Appointment Date: {{ $date }}</li>
        <li>Appointment Time: {{ $time }}</li>
    </ul>

    <hr>
    <p><strong>Invoice:</strong> The invoice for this payment is attached to this email.</p>
    
    <p>Thank you for completing the payment. Please keep this email for your records. If you have any questions or require further assistance, feel free to contact us.</p>

    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
