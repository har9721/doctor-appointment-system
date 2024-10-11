<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment</title>
</head>
<body>
    <h1>New Appointment</h1>
    <p>Dear Dr. {{ $name }},</p>
    <p>You have a new appointment with {{ $patientName }} scheduled for {{ $date }} at {{ $time }}.</p>
    <p>Please make sure to attend the appointment on time.</p>
    <p>Thank you!.</p>
</body>
</html>