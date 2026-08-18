<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>A Special Gift Just For You!</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; color: #1f2937;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 40px 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
        <h1 style="color: #e11d48; margin-top: 0; font-size: 28px;">It's been a month! 🎉</h1>
        
        <p style="font-size: 16px; line-height: 1.6;">Hi {{ $user->name }},</p>
        
        <p style="font-size: 16px; line-height: 1.6;">Thank you for being a part of the Garikothay family! We noticed you've been with us for a while, and we wanted to show our appreciation.</p>
        
        <div style="background-color: #fff1f2; border: 2px dashed #f43f5e; border-radius: 8px; padding: 20px; margin: 30px 0;">
            <p style="margin: 0; font-size: 14px; color: #9f1239; text-transform: uppercase; font-weight: bold;">Use this coupon code</p>
            <h2 style="margin: 10px 0 0; color: #e11d48; font-size: 32px; letter-spacing: 2px;">{{ $couponCode }}</h2>
        </div>
        
        <p style="font-size: 16px; line-height: 1.6;">Enjoy 10% off your next purchase with us. Hurry, this code expires in 7 days!</p>
        
        <div style="margin: 30px 0;">
            <a href="{{ config('app.url') }}" style="background-color: #e11d48; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px;">Shop Now</a>
        </div>
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
            If you have any questions, feel free to reply to this email.<br>
            <strong>The Garikothay Team</strong>
        </p>
    </div>
</body>
</html>
