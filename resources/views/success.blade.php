<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        .success { color: #4CAF50; }
        p { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Payment Successful</h1>
    <div class="success">
        <p>Transaction ID: {{ $transaction_id }}</p>
        <p>Amount: {{ $amount }} VND</p>
        <p>Order ID: {{ $order_id }}</p>
    </div>
    <a href="{{ url('/test-payment') }}">Make Another Payment</a>
</body>
</html>
