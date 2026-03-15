<html>
<body>
    <h5>Dear {{ $patientName }},</h5>
    <p>Your appointment has been scheduled successfully.</p>

    <p>To confirm your booking, please complete the advance payment within 1 hour using the link below:</p>
    
    <h3>Appointment Details:</h3>
    <ul>
        <li>Appointment Number: <strong>{{ $appointmentNo }}</strong></li>
        <li>Doctor's Name: Dr. {{ $name }}</li>
        <li>Appointment Date: {{ $date }}</li>
        <li>Appointment Time: {{ $time }}</li>
        <li>Total Amount: <span>&#8377;</span>{{ $amount }}</li>
    </ul>
    
    <p><Strong>Payment Link :</Strong></p>
    <a href="{{ route('appointments.completed-list') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Make Payment</a>

    <br/>

    <p>Kindly note that the appointment will be confirmed only after payment is received. If payment is not completed within 5 minutes, the slot may be released.</p>

    <br/>

    <p>If you have already made the payment, please ignore this message.</p>

    <p>Thank you for using our service.</p>
    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
