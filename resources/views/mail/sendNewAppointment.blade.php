<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment</title>
</head>
<body>
    <h5>Dear Dr. {{ $name }},</h5>

    <p>You have a new appointment with {{ $patientName }} scheduled for {{ $date }} at {{ $time }}.</p>

    <p>Appointment Number: <strong>{{ $appointmentNo }}</strong></p>

    <p>Please make sure to attend the appointment on time.</p>

    <p>Thank you!.</p>

    <p>Regards,</p>

    <p>Doctor Management System</p>
</body>
</html>
