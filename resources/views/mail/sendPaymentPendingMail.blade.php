<html>
<body>
    <h4>Dear {{ $patientName }},</h3>
    <p>please complete the payment for your last appointment with {{ $doctorName }}</p>
    
    <h5>Appointment Details:</h5>
    <ul>
        <li>Doctor's Name: Dr. {{ $doctorName }}</li>
        <li>Appointment Date: {{ $date }}</li>
        <li>Appointment Time: {{ $time }}</li>
        <li>Amount : <span>&#8377;</span>{{ $amount }}</li>
    </ul>
    
    <p>If you have any questions or require further assistance, feel free to contact us.</p>

    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
