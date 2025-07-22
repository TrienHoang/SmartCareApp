<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test VNPay Payment</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 400px; margin: 0 auto; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test VNPay Payment</h1>
        <form action="{{ route('test.payment') }}" method="POST">
            @csrf
            <label for="amount">Amount (VND):</label>
            <input type="number" name="amount" id="amount" value="10000" min="1000" required>
            <button type="submit">Pay Now</button>
        </form>
    </div>
</body>
</html>
