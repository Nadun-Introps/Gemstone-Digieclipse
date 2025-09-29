<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bid Payment Failed</title>
</head>
<body>
    <h2>Bid Payment Failed</h2>

    <p>Dear {{ $customerName }},</p>

    <p>Unfortunately, your bid payment could not be processed. Here are the details:</p>

    <div style="background: #f9f9f9; padding: 15px; margin: 15px 0;">
        <h3>Bid Details:</h3>
        <p><strong>Product:</strong> {{ $bid['product_name'] }}</p>
        <p><strong>Bid Amount:</strong> Rs. {{ number_format($bid['bid_amount'], 2) }}</p>
        <p><strong>Bid ID:</strong> {{ $bid['sku'] }}</p>
        <p><strong>Error:</strong> {{ $errorMessage }}</p>
    </div>

    <p>Please try placing your bid again or contact our support team if the issue persists.</p>

    <br>
    <p>Best regards,<br>Your Auction Team</p>
</body>
</html>
