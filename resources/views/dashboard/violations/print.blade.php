<!DOCTYPE html>
<html>
<head>
    <title>Order Invoice</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Tajawal', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            background-color: #f8f9fa;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        h1, h2 {
            color: #333;
            margin: 0;
            padding-bottom: 5px;
        }

        h1 {
            font-size: 28px;
            border-bottom: 2px solid #3bcf98;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px;
            color: #444;
            margin-bottom: 10px;
            border-bottom: 1px solid #3bcf98;
            padding-bottom: 5px;
        }

        /* Header Styling */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .company-logo img {
            width: 150px;
            height: auto;
        }

        .company-details {
            text-align: right;
            color: #555;
            font-size: 14px;
        }

        /* Table Styling */
        .invoice-table {
            width: 50%; /* col-6 width */
            float: right;
            clear: both;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #e9ecef;
            text-align: left;
        }

        th {
            background-color: #3bcf98;
            color: #fff;
            font-weight: bold;
        }

        td {
            background-color: #f8f9fa;
        }

        /* Button Styling */
        .print-button {
            display: inline-block;
            padding: 12px 25px;
            margin-top: 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .print-button:hover {
            background-color: #218838;
        }

        /* Print Styling */
        @media print {
            /* Ensure the table floats correctly in print */
            .invoice-table {
                width: 70%;
                float: right;
                clear: both;
                margin-top: 20px;
            }

            .container {
                width: 100%;
                box-shadow: none;
                padding: 0;
                margin: 0;
                background-color: #fff;
            }

            table, th, td {
                border: 1px solid #000;
                width: 100% !important;
                border-collapse: collapse;
            }

            th {
                background-color: #3bcf98;
                color: #fff;
            }

            td {
                background-color: #f8f9fa;
            }

            /* Remove the print button from the printed version */
            .print-button {
                display: none;
            }

            /* Ensure that the header is clean and concise */
            h1, h2, .company-details, .invoice-header {
                padding: 0;
                margin: 0;
            }

            /* Remove any unnecessary page margins or padding */
            body {
                margin: 0;
                padding: 0;
            }
        }

        .payment-details p {
            margin: 10px 0;
            font-size: 14px;
            color: #555;
        }

        .payment-details strong {
            font-weight: bold;
            color: #333;
        }


    </style>
    <script>
        // Trigger print on page load
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>

<div class="container">
    <div class="invoice-header">
        <div class="company-logo">
            <img src="{{ url('storage/' . setting('logo')) }}" alt="Company Logo">
        </div>
        <div class="company-details">
            <h2>{{setting('name')}}</h2>
            <p>1234 Street Address, City, State, ZIP</p>
            <p>{{setting('whatsapp')}}</p>
            <p>{{setting('email')}}</p>
        </div>
    </div>


    <div class="section">
        <h1>Order Invoice</h1>

        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Order Details:</strong> {{ $order->order_details }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="section">
        <h2>Customer Information</h2>
        <p><strong>Customer ID:</strong> {{ $order->user->id }}</p>
        <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
    </div>

    <div class="section invoice-table">
        <h2>Payment Information</h2>
        <div class="payment-details">
            <p><strong>Payment ID:</strong> {{ $order->payment->id }}</p>
            <p><strong>Value:</strong> ${{ number_format($order->payment->value, 2) }}</p>
            <p><strong>Status:</strong> {{ $order->payment->status == 1 ? 'Paid' : 'Pending' }}</p>
            <p><strong>Violation ID:</strong> {{ $order->payment->violation_id ?? 'N/A' }}</p>
            <p><strong>Violation Details:</strong> {{ $order->payment->note ?? 'N/A' }}</p>
        </div>
    </div>





    <a href="javascript:void(0)" class="print-button" onclick="window.print()">Print Invoice</a>
</div>

</body>
</html>
