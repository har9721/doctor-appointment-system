<html>
<body>
    <h5>Dear {{ $patientName }},</h5>
    <p>Your recent appointment with Dr. {{ $doctorName }} on {{ $date }} has been completed.</p>
    <p>To finalize this appointment, kindly complete the payment.</p>
    
    <h3>Appointment Details:</h3>
    <ul>
        <li>Appointment Number: <strong>{{ $appointmentNo }}</strong></li>
        <li>Doctor's Name: Dr. {{ $doctorName }}</li>
        <li>Specialty: {{ $specialty }}</li>
        <li>Appointment Date: {{ $date }}</li>
        <li>Appointment Time: {{ $time }}</li>
        <li>Total Amount: <span>&#8377;</span>{{ $amount }}</li>
    </ul>
    
    <a href="{{ route('appointments.completed-list') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Make Payment</a>

    <p>Thank you for using our service.</p>
    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
