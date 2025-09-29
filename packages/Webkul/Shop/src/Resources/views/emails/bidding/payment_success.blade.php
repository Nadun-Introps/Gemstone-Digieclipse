<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bid Payment Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; text-align: center; }
        .content { background: #ffffff; padding: 20px; }
        .bid-details { background: #f9f9f9; padding: 15px; margin: 15px 0; border-left: 4px solid #007bff; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bid Payment Successful!</h1>
        </div>

        <div class="content">
            <p>Dear {{ $customerName }},</p>

            <p>Your bid payment has been successfully processed. Here are your bid details:</p>

            <div class="bid-details">
                <h3>Bid Details:</h3>
                <p><strong>Product:</strong> {{ $bid['product_name'] }}</p>
                <p><strong>Bid Amount:</strong> Rs. {{ number_format($bid['bid_amount'], 2) }}</p>
                <p><strong>Bid ID:</strong> {{ $bid['sku'] }}</p>
                <p><strong>Auction Ends:</strong> {{ date('F jS, Y g:i A', strtotime($bid['auction_end'])) }}</p>
                <p><strong>Payment Date:</strong> {{ $paymentDate }}</p>
                <p><strong>Payment Reference:</strong> {{ $paymentIntentId }}</p>
            </div>

            <p>You can view your bid status anytime by visiting our website.</p>
            <p>Thank you for participating in our auction!</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>Your Auction Team</p>
        </div>
    </div>
</body>
</html>
