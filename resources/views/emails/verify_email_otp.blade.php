<x-transactional-email>
    <x-slot:purpose>
        🔐 Verify Your Email
    </x-slot>

    <x-slot:greeting>
        Hello, {{ $name ?? 'Customer' }}! 👋
    </x-slot>

    <p style="margin-top: 0;">Welcome to Garikothay.com.</p>
    <p>You’re one step away from completing your account registration. Please use the One-Time Password (OTP) below to verify your email address.</p>

    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; text-align: center; margin: 24px 0;">
        <span style="font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #0f172a;">{{ $otp ?? '123456' }}</span>
    </div>

    <p>This OTP expires in {{ $expiry ?? '10 minutes' }}.</p>
    <p>For your security, never share this OTP with anyone, including Garikothay representatives.</p>

    <x-slot:reference>
        Reference ID: {{ $reference_id ?? 'N/A' }}<br>
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('Y-m-d H:i:s') }} (GMT+6)
    </x-slot>

    <x-slot:cta>
        <div style="text-align: center;">
            <a href="{{ $verify_url ?? '#' }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">Verify Your Email</a>
        </div>
    </x-slot>

    <x-slot:nextStep>
        Once your email is verified, your Garikothay account registration will be completed.<br>
        <span style="font-size: 13px; font-weight: normal; color: #475569; display: block; margin-top: 8px;">If you did not attempt to create a Garikothay account, no further action is required. You may safely ignore this email.</span>
    </x-slot>
</x-transactional-email>
