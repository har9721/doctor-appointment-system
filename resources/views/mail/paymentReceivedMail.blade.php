<!DOCTYPE html>
<html>
<head>
    <title>Payment Received</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <p>Dear Dr. {{ $doctorName }},</p>

    <p>This is to inform you that the payment for the appointment with <strong>{{ $patientName }}</strong> on <strong>{{ $date }}</strong> has been successfully received.</p>

    <p>You can now proceed with adding the prescription for this appointment.</p>

    <ul>
        <li><strong>Patient Name:</strong> {{ $patientName }}</li>
        <li><strong>Appointment Date:</strong> {{ $date }}</li>
        <li><strong>Payment Status:</strong> <span style="color: green;">Paid ✅</span></li>
    </ul>

    <p>
        <a href="{{ $prescriptionLink }}" style="display: inline-block; padding: 10px 15px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Add Prescription
        </a>
    </p>

    <p>regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
