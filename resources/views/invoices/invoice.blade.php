<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
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
                    123 Street, City, Country<br>
                    Email: admin@yopmail.com<br>
                    Phone: +91 9876543210
                </td>
            </tr>
        </table>

        <h2 class="invoice-title">Invoice</h2>

        <table class="details">
            <tr>
                <td><strong>Payment ID:</strong></td>
                <td>{{ $invoiceData['transaction_id'] }}</td>
            </tr>
            <tr>
                <td><strong>Amount:</strong></td>
                <td>&#8377; {{ number_format($invoiceData['amount'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($invoiceData['payment_status']) }}</td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ ucfirst($invoiceData['method']) }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $invoiceData['email'] }}</td>
            </tr>
            <tr>
                <td><strong>Contact:</strong></td>
                <td>{{ $invoiceData['mobile'] }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $invoiceData['paymentDate'] }}</td>
            </tr>
        </table>

        <div class="footer">
            Thank you for your payment!<br>
            <small>This is an auto-generated invoice. No signature required.</small>
        </div>
    </div>
</body>
</html>

