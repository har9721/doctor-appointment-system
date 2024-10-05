<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>
<body>
    <h1>Appointment Confirmation</h1>
    <p>Dear {{ $patientName }},</p>
    <p>Your appointment with Dr. {{ $name }} has been confirmed for {{ $date }} at {{ $time }}.</p>
    <p>Thank you for using our service!</p>
</body>
</html>
