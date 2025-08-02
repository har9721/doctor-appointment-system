<!DOCTYPE html>
<html>
<head>
    <title>Your Prescription from {{ $doctor_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { padding: 20px; border-radius: 10px;}
        h2 { color: #2c3e50; text-align: center; }
        .details { margin-bottom: 20px; }
        .details p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: #fff; }
        .instructions { margin-top: 20px; padding: 10px; background: #f9f9f9; border-left: 4px solid #3498db; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Prescription Details</h2>
        
        <div class="details">
            <p><strong>Appointment Number :</strong> {{ $appointmentNo }} </p>
            <p><strong>Patient Name:</strong> {{ $patient_name }}</p>
            <p><strong>Doctor:</strong> {{ $doctor_name }}</p>
            <p><strong>Appointment Date:</strong> {{ $appointment_date }}</p>
        </div>

        <h3>Prescribed Medicines</h3>
        <table>
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Dosage</th>
                    <th>Instructions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                <tr>
                    <td>{{ $medicine['medicine'] }}</td>
                    <td>{{ $medicine['dosage'] }}</td>
                    <td>{{ $medicine['instruction'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($instructions)
        <div class="instructions">
            <h3> Additional Instructions</h3>
            <p>{{ $instructions }}</p>
        </div>
        @endif

        <p>For any queries, please contact our clinic.</p>

        <p>Regards,</p>
        <p>Doctor Management System</p>
    </div>
</body>
</html>
