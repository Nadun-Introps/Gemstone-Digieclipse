<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>You've Been Outbid</title>
</head>

<body>
    <h2>You've Been Outbid!</h2>

    <p>Dear {{ $customerName }},</p>

    <p>Another bidder has placed a higher bid on the item you were interested in:</p>

    <div style="background: #f9f9f9; padding: 15px; margin: 15px 0;">
        <h3>Product:</h3>
        <p><strong>{{ $productName }}</strong></p>
        <p><strong>Current Highest Bid:</strong> Rs. {{ number_format($currentBid, 2) }}</p>
        <p><strong>Your Previous Bid:</strong> Rs. {{ number_format($yourBid, 2) }}</p>
        <p><strong>Auction Ends:</strong> {{ $auctionEnd }}</p>
    </div>

    <p>If you'd like to increase your bid, you can do so before the auction ends.</p>

    <a href="{{ $productUrl }}"
        style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Place New Bid
    </a>

    <br><br>
    <p>Best regards,<br>Your Auction Team</p>
</body>

</html>
