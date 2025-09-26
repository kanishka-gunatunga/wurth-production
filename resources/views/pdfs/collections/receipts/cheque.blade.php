<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Invoice</title>
    <style>
       @page {
            margin: 60px 25px; 
        }
        p, h1, h2, h3, h4, h5, h6 {
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        p {
            line-height: 1.5; 
        }

        td, th {
            padding: 5px; 
        }
        .invoice-box {
            width: 100%;

        }
        .invoice-header {
            margin-bottom: 20px;
        }
        .invoice-header h2 {
            margin: 0;
        }
        .details, .items {
            width: 100%;
            margin-bottom: 20px;
        }

        .items th, .items td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.1);
            z-index: 0;
            pointer-events: none;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<?php if($is_duplicate == 1){ ?>
    <div class="watermark">DUPLICATE COPY</div>
<?php } ?>
    <div class="invoice-box">
        <div class="invoice-header">
            <h2>Payment Receipt</h2>
            <p>Invoice/Cheque No #: {{ $invoice->invoice_or_cheque_no }}</p>
            <p>Payment ID #: {{ $payment->id }}</p>
            <p>Date: {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</p>
            <p>ADM : {{ $adm->name }}</p>
        </div>

        <table class="details">
            <tr>
                <td><strong>Customer Name:</strong> {{ $customer->name }}</td>
                <td><strong>Payment Type:</strong>Cheque Payment</td>
                
            </tr>
            <tr>
                <td><strong>Email:</strong> {{ $customer->email }}</td>
                <td><strong>Amount Paid:</strong> Rs. {{ number_format($payment->final_payment, 2) }}</td>
                
            </tr>
            <tr>
                <td><strong>Cheque Number:</strong> {{$payment->cheque_number}}</td>
                <td><strong>Cheque Date:</strong> {{$payment->cheque_date}}</td>
            </tr>
            <tr>
                <td><strong>Bank Name:</strong> {{$payment->bank_name}}</td>
                <td><strong>Branch Name:</strong> {{$payment->branch_name}}</td>
            </tr>
            <tr>
                <td><strong>Post - Dated Cheque:</strong> {{$payment->post_dated}}</td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Settled Amount</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount ({{ $payment->discount }}%)</td>
                    <td> {{ number_format(($payment->amount * $payment->discount) / 100, 2) }}</td>
                </tr>
                <tr>
                    <td>Paid Amount</td>
                    <td>{{ number_format($payment->final_payment, 2) }}</td>
                </tr>
            </tbody>
        </table>

       
    </div>
</body>
</html>
