<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        .fail { color: #f44336; }
        p { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Payment Failed</h1>
    <div class="fail">
        <p>Something went wrong. Please try again.</p>
    </div>
    <a href="{{ url('/test-payment') }}">Try Again</a>
</body>
</html>
