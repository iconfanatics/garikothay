<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>We've received your message</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; margin: 0; color: #374151;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="color: #e11d48; margin-top: 0;">Hello {{ $customerName }},</h2>
        
        <p>Thank you for reaching out to us. This is an automated response to confirm that we have received your message regarding <strong>"{{ $messageSubject }}"</strong>.</p>
        
        <p>Your support ticket number is: <strong style="color: #e11d48; font-size: 16px;">{{ $ticketNumber }}</strong></p>
        
        <p>Our support team will review your message and get back to you within 24 hours. If you have any additional information to add, simply reply to this email.</p>
        
        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>The Garikothay Team</strong>
        </p>
    </div>
</body>
</html>
