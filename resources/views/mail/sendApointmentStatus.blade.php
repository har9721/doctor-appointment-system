<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>
<body>
    <h5>Dear {{ $patientName }},</h5>
    @if($msg1)
        <p>{{ $msg1 }}</p>
    @endif

    @if($msg2)
        <p>{{ $msg2 }}</p>
    @endif

    <p>Appointment Number: <strong>{{ $appointmentNo }}</strong></p>

    <p>Thank you for using our service!</p>
    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
