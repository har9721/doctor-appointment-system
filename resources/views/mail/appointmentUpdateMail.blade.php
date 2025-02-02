<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Update Mail</title>
</head>
<body>
    <h5>Dear {{ $patientName }},</h5>
    <p>Your appointment details have been updated. Please find the new details below:</p>
    <p><b>Doctor Name </b>: {{ $name }}</p>
    <p><b>Date </b>: {{ $date }}</p>
    <p><b>Time </b>: {{ $time }}</p>
    <p>Please make sure to attend the appointment on time.</p>
    <p>Thank you for using our service!</p>
    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
