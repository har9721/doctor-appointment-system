<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescriptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            text-align: center;
        }
        .header img {
            max-width: 150px;
        }
        .company-details {
            text-align: right;
        }
        .invoice-title {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }
        .details {
            width: 100%;
            margin-bottom: 20px;
        }
        .details td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .qr-code {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table width="100%">
            <tr>
                <td class="header">
                    <img src="{{ public_path('assets/Images/stethoscope.jpg') }}" alt="Company Logo">
                </td>
                <td class="company-details">
                    <strong>Doctor Management System</strong>
                    <br>
                    123 Street, Mumbai, India<br>
                    Email: admin@yopmail.com<br>
                    Phone: +91 9876543210
                </td>
            </tr>
        </table>

        <h2 class="invoice-title">Prescribed Medicines</h2>

        <table class="details">
            <thead>
                <tr>
                    <th>SrNo.</th>
                    <th>Medicine Name</th>
                    <th>Dosage</th>
                    <th>Instructions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;?>
                @foreach($data as $medicine)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $medicine['medicine'] }}</td>
                    <td>{{ $medicine['dosage'] }}</td>
                    <td>{{ $medicine['instruction'] }}</td>
                </tr>
                <?php $i++; ?>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Thank you for using our service!<br>
            <small>This is an auto-generated prescription. No signature required.</small>
        </div>
    </div>
</body>
</html>

