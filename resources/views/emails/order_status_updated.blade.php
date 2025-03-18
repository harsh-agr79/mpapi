<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px solid #007bff;
        }
        .header h2 {
            color: #007bff;
            margin: 0;
        }
        .content {
            padding: 20px;
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }
        .status {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #ddd;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h2>Order Update - #{{ $order->id }}</h2>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $order->customer->billing_full_name }}</strong>,</p>
            <p>We wanted to let you know that the status of your order <strong>#{{ $order->id }}</strong> has been updated.</p>

            <div class="status">
                Current Status: <strong>{{ strtoupper($order->current_status) }}</strong>
            </div>

            <p>If you have any questions, feel free to <a href="{{ url('/contact') }}" style="color:#007bff;">contact us</a>.</p>

            <p>Click the button below to view your order details:</p>
            <a href="{{ url('/orders/' . $order->id) }}" class="btn">View Order</a>
        </div>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p><a href="{{ url('/') }}">Visit Our Website</a></p>
        </div>
    </div>

</body>
</html>
