<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Review your recent order</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; margin: 0; color: #374151;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="color: #e11d48; margin-top: 0;">How did we do?</h2>
        
        <p>Hi {{ $order->user->name ?? 'Customer' }},</p>
        
        <p>We hope you are enjoying your recent purchase from Garikothay (Order #{{ $order->order_number }}). We'd love to hear your thoughts!</p>
        
        <p>Your feedback helps us improve and helps other customers make better decisions.</p>
        
        <div style="margin: 30px 0;">
            <a href="{{ route('customer.orders.show', $order) }}" style="background-color: #e11d48; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; display: inline-block;">Write a Review</a>
        </div>
        
        <p style="font-size: 13px; color: #6b7280;">If the button above doesn't work, log into your account and navigate to My Orders.</p>
        
        <p style="margin-top: 30px;">
            Thank you for choosing us!<br>
            <strong>The Garikothay Team</strong>
        </p>
    </div>
</body>
</html>
