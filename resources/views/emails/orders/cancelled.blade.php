<x-transactional-email>
    <x-slot:purpose>
        ❌ Your Order Has Been Cancelled
    </x-slot>

    <x-slot:greeting>
        Hello, {{ $order->user->name ?? $order->billing_address['name'] ?? 'Customer' }}! 👋
    </x-slot>

    <p style="margin-top: 0;">Your order <strong>{{ $order->order_number }}</strong> has been successfully cancelled.</p>
    
    @if(isset($cancellationReason))
    <p><strong>Cancellation Reason:</strong> {{ $cancellationReason }}</p>
    @endif

    <p>If a payment was already made, any applicable refund will be processed according to Garikothay's applicable refund policy.</p>

    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin: 24px 0;">
        <h3 style="margin-top: 0; color: #0f172a; font-size: 16px;">Order Details</h3>
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order ID:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order Date & Time:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ $order->created_at->timezone('Asia/Dhaka')->format('M d, Y h:i A') }} (GMT+6)</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Cancellation Date & Time:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ now()->timezone('Asia/Dhaka')->format('M d, Y h:i A') }} (GMT+6)</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order Status:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #e11d48;">Cancelled</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Payment Status:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ ucfirst($order->payment_status->value ?? 'Pending') }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order Amount:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">৳{{ number_format((float) $order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <x-slot:reference>
        Reference ID: CAN-{{ $order->order_number }}<br>
        Status: Order Cancelled<br>
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('M d, Y h:i A') }} (GMT+6)
    </x-slot>

    <x-slot:cta>
        <div style="text-align: center;">
            <a href="{{ route('customer.order.show', $order->order_number) }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">View Order Details</a>
        </div>
    </x-slot>

    <x-slot:nextStep>
        <span style="font-size: 13px; font-weight: normal; color: #475569; display: block; margin-top: 8px;">If you believe this order was cancelled incorrectly or you need assistance regarding a refund, please contact Garikothay Support & mention your Reference ID.</span>
    </x-slot>
</x-transactional-email>
