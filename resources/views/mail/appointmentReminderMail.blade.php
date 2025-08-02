<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Reminder</title>
</head>
<body>
    <h2>⏰ Appointment Reminder</h2>
    <h5>Dear {{ $name }},</h5>
    <p>This is a friendly reminder for your upcoming appointment.</p>
    <p>Please find the details below:</p>

    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <td><strong>{{ $label }}:</strong></td>
            <td>{{ $partner_name }}</td>
        </tr>
        <tr>
            <td><strong>Appointment Number:</strong></td>
            <td>{{ $appointmentNo }}</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ $appointmentDate }}</td>
        </tr>
        <tr>
            <td><strong>Time:</strong></td>
            <td>{{ $time }}</td>
        </tr>
    </table>

    <p>If you have any questions or need to reschedule, please contact us at <a href="mailto:{{ $supportEmail }}">
        {{ $supportEmail }}</a>.
    </p>

    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
