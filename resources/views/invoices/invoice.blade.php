<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
        }
        .invoice-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .invoice-title {
            font-size: 22px;
            font-weight: 700;
        }
        .section-title {
            font-weight: 600;
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .amount-highlight {
            font-size: 18px;
            font-weight: 600;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-partial {
            background: #fff3cd;
            color: #856404;
        }
        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }
        hr {
            margin: 20px 0;
        }
    </style>
</head>

<body>

<div class="container mt-5 mb-5">
    <div class="invoice-box">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="invoice-title">Appointment Invoice</div>
                <small class="text-muted">Invoice No: {{ $invoiceData['appointment_no'] }}</small><br>
                <small class="text-muted">Date: {{ $invoiceData['paymentDate'] }}</small>
            </div>

            <div>
                @if($invoiceData['payment_status'] == 'completed')
                    <span class="status-badge status-paid">Fully Paid</span>
                @elseif($invoiceData['payment_status'] == 'partial')
                    <span class="status-badge status-partial">Partially Paid</span>
                @else
                    <span class="status-badge status-failed">Payment Failed</span>
                @endif
            </div>
        </div>

        <!-- Patient & Appointment Info -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="section-title">Patient Details</div>
                <strong>{{ $invoiceData['patientName'] }}</strong><br>
                {{ $invoiceData['email'] }}<br>
                {{ $invoiceData['mobile'] }}
            </div>

            <div class="col-md-6 text-md-right">
                <div class="section-title">Appointment Details</div>
                Doctor: {{ $invoiceData['doctorName'] }}<br>
                Date: {{ $invoiceData['appointmentDate'] }}<br>
                Time: {{ $invoiceData['time'] }}
            </div>
        </div>

        <hr>

        <!-- Charges -->
        <div class="section-title">Charges</div>
        <div class="d-flex justify-content-between">
            <span>Consultation Fee</span>
            <span>{{ number_format($invoiceData['amount'], 2) }}</span>
        </div>

        <hr>

        <!-- Payment Breakdown -->
        <div class="section-title">Payment Breakdown</div>

        @if($invoiceData['advance_amount'] > 0)
        <div class="d-flex justify-content-between">
            <span>Advance Paid</span>
            <span class="text-success">
                - {{ number_format($invoiceData['advance_amount'], 2) }}
            </span>
        </div>
        <small class="text-muted">
            Transaction ID: {{ $invoiceData['advance_transaction_id'] }}
        </small>
        @endif

        @if($invoiceData['remaining_amount'] > 0)
        <div class="d-flex justify-content-between mt-2">
            <span>Remaining Payment</span>
            <span class="text-success">
                - {{ number_format($invoiceData['remaining_amount'], 2) }}
            </span>
        </div>
        <small class="text-muted">
            Transaction ID: {{ $invoiceData['remaining_transaction_id'] }}
        </small>
        @endif

        <hr>

        <!-- Total Paid -->
        <div class="d-flex justify-content-between amount-highlight">
            <span>Total Paid</span>
            @if($invoiceData['method'] == 'offline')
                <span>
                    {{ number_format($invoiceData['amount'], 2) }}
                </span>
            @else
                <span>
                    {{ number_format($invoiceData['advance_amount'] + $invoiceData['remaining_amount'], 2) }}
                </span>
            @endif
        </div>

        @if($invoiceData['payment_status'] == 'partial')
        <div class="d-flex justify-content-between amount-highlight mt-2 text-danger">
            <span>Balance Due</span>
            <span>
                {{ number_format($invoiceData['amount'] - ($invoiceData['advance_amount'] + $invoiceData['remaining_amount']), 2) }}
            </span>
        </div>
        @endif

        <hr>

        <!-- Footer -->
        <div class="text-center mt-4">
            <small class="text-muted">
                Thank you for choosing our clinic.  
                For any queries, contact support.
            </small>
        </div>

    </div>
</div>

</body>
</html>

