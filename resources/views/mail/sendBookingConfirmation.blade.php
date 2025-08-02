<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>
<body>
    <h5>Dear {{ $patientName }},</h5>

    <p>Your appointment has been successfully booked.</p>
    <ul>
        <li><strong>Appointment Number:</strong> {{ $appointmentNumber }}</li>
        <li><strong>Doctor:</strong> Dr. {{ $name }}</li>
        <li><strong>Date & Time:</strong> {{ $date }} at {{ $time }}</li>
        <li><strong>Location:</strong>Main Clinic, Health Avenue</li>
    </ul>

    <p>Please make sure to arrive 10–15 minutes early. If you have any questions or need to reschedule, feel free to contact us.</p>

    <p>Thank you for choosing our services.</p>

    <p>Warm regards,</p>

    <p><strong>Doctor Appointment Management Team</strong></p>

    <p><small>This is an automated email. Please do not reply directly.</small></p>
</body>
</html>
