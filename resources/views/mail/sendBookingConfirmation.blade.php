<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>
<body>
    <h5>Dear {{ $patientName }},</h5>
    <p>Your appointment with Dr. {{ $name }} has been scheduled for {{ $date }} at {{ $time }}.</p>
    <p>Please make sure to attend the appointment on time.</p>
    <p>Thank you for using our service!</p>
    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
