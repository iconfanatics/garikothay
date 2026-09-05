<x-transactional-email>
    <x-slot:purpose>
        ⭐ We'd Love to Hear From You
    </x-slot>

    <x-slot:greeting>
        Hello, {{ $order->user->name ?? $order->billing_address['name'] ?? 'Customer' }}! 👋
    </x-slot>

    <p style="margin-top: 0;">Your order <strong>{{ $order->order_number }}</strong> has been delivered successfully.</p>
    <p>We hope you’re happy with your purchase & the service you received. Your feedback helps us improve & helps other customers make informed decisions.</p>

    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin: 24px 0;">
        <h3 style="margin-top: 0; color: #0f172a; font-size: 16px;">Your Order</h3>
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order ID:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Delivered Date:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ $order->updated_at->timezone('Asia/Dhaka')->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order Status:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #16a34a;">Delivered</td>
            </tr>
        </table>
    </div>

    <x-slot:reference>
        Reference ID: REV-{{ $order->order_number }}<br>
        Status: Feedback Requested<br>
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('M d, Y h:i A') }} (GMT+6)
    </x-slot>

    <x-slot:cta>
        <div style="text-align: center;">
            <a href="{{ route('customer.order.show', $order->order_number) }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">Write a Review</a>
        </div>
        <div style="text-align: center; font-size: 14px; color: #475569; margin-top: 16px;">
            You can rate your experience & share your feedback about your purchase.<br>
            Your review may help other Garikothay customers make better decisions.
        </div>
    </x-slot>

    <x-slot:nextStep>
        Your feedback is valuable to us.<br>
        <span style="font-size: 13px; font-weight: normal; color: #475569; display: block; margin-top: 8px;">Whether your experience was excellent or there is something we could have done better, we’d appreciate hearing from you.<br>If you experienced any issue with your order, please contact Garikothay Support directly so we can assist you.</span>
    </x-slot>
</x-transactional-email>
