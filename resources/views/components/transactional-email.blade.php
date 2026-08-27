<x-mail::message>
<div style="background-color: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; text-align: center; border: 1px solid #e2e8f0; color: #334155;">
    <strong>Official Communication</strong><br>
    This is an official communication from Garikothay.com regarding your recent activity.
</div>

@if(isset($purpose))
<div style="font-size: 18px; font-weight: 700; margin-bottom: 24px; text-align: center; color: #0f172a;">
    {{ $purpose }}
</div>
@endif

@if(isset($greeting))
<div style="margin-bottom: 20px; font-size: 16px; color: #334155;">
    {{ $greeting }}
</div>
@endif

<div style="color: #334155; font-size: 16px; line-height: 1.6;">
{{ $slot }}
</div>

@if(isset($reference))
<div style="background-color: #f1f5f9; padding: 16px; border-radius: 8px; margin-top: 32px; font-size: 14px; color: #475569; line-height: 1.6;">
    {!! nl2br(e(trim($reference))) !!}
</div>
@endif

@if(isset($cta))
<div style="text-align: center; margin-top: 32px; margin-bottom: 32px;">
    {{ $cta }}
</div>
@endif

@if(isset($nextStep))
<div style="margin-top: 24px; font-size: 15px; font-weight: 600; color: #334155;">
    {{ $nextStep }}
</div>
@endif

<hr style="border: none; border-top: 1px solid #e2e8f0; margin: 32px 0;">

<div style="font-size: 13px; color: #64748b; margin-top: 24px; line-height: 1.5;">
    <strong style="color: #334155;">Security Notice</strong><br>
    Never share OTP, passwords, PINs, or payment information with anyone.<br>
    Always verify that emails come from @garikothay.com and that links open only on https://garikothay.com.
</div>

<div style="font-size: 13px; color: #64748b; margin-top: 24px; line-height: 1.5;">
    <strong style="color: #334155;">Support</strong><br>
    Need help?<br>
    📞 +8809647241999 (9:00 AM–7:00 PM GMT+6)<br>
    💬 +8801314666611 (WhatsApp text only)<br>
    ✉️ hello@garikothay.com<br>
    Please mention your Reference ID when contacting support.
</div>

<div style="font-size: 13px; color: #64748b; margin-top: 24px; line-height: 1.5;">
    <strong style="color: #334155;">About This Email</strong><br>
    This is an automated transactional email sent regarding your recent activity.<br>
    Transactional emails cannot be unsubscribed because they contain important information.<br>
    Garikothay.com never includes promotional or marketing content in transactional emails.<br>
    Please do not reply to this email.
</div>
</x-mail::message>
