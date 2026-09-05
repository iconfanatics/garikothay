<x-transactional-email>
    <x-slot:purpose>
        🟢 Order Confirmation
    </x-slot>

    <x-slot:greeting>
        Hello, {{ $order->user->name ?? $order->billing_address['name'] ?? 'Customer' }}! 👋
    </x-slot>

    <p style="margin-top: 0;">Thank you for your order with Garikothay.com.</p>
    <p>We’re pleased to confirm that your order has been successfully received & is now being processed.</p>

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
                <td style="padding: 4px 0; color: #475569;">Payment Status:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ ucfirst($order->payment_status->value ?? 'Pending') }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Order Status:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ ucfirst($order->status->value ?? 'New') }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Total Amount:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">৳{{ number_format((float) $order->total, 2) }}</td>
            </tr>
        </table>

        <h3 style="margin-top: 24px; color: #0f172a; font-size: 16px; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">Items</h3>
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            @foreach($order->items as $item)
            <tr>
                <td style="padding: 8px 0; color: #334155; border-bottom: 1px solid #e2e8f0;">
                    {{ $item->product->name ?? 'Product' }}<br>
                    <span style="font-size: 12px; color: #64748b;">Qty: {{ $item->quantity }}</span>
                </td>
                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0;">
                    ৳{{ number_format((float) $item->subtotal, 2) }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td style="padding: 8px 0; color: #475569; text-align: right;">Subtotal:</td>
                <td style="padding: 8px 0; font-weight: 600; text-align: right; color: #0f172a;">৳{{ number_format((float) $order->subtotal, 2) }}</td>
            </tr>
            @if($order->shipping_charge > 0)
            <tr>
                <td style="padding: 8px 0; color: #475569; text-align: right;">Shipping Fee:</td>
                <td style="padding: 8px 0; font-weight: 600; text-align: right; color: #0f172a;">৳{{ number_format((float) $order->shipping_charge, 2) }}</td>
            </tr>
            @endif
            @if($order->discount_amount > 0)
            <tr>
                <td style="padding: 8px 0; color: #475569; text-align: right;">Discount:</td>
                <td style="padding: 8px 0; font-weight: 600; text-align: right; color: #0f172a;">-৳{{ number_format((float) $order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #0f172a; text-align: right; font-weight: 700; font-size: 15px;">Total:</td>
                <td style="padding: 8px 0; font-weight: 700; text-align: right; color: #e11d48; font-size: 15px;">৳{{ number_format((float) $order->total, 2) }}</td>
            </tr>
        </table>

        <h3 style="margin-top: 24px; color: #0f172a; font-size: 16px;">Delivery Information</h3>
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <tr>
                <td style="padding: 4px 0; color: #475569;">Recipient:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ $order->shipping_address['name'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Phone:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ Str::mask($order->shipping_address['phone'] ?? '', '*', 3, 4) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Delivery Address:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">
                    {{ $order->shipping_address['address'] ?? '' }}<br>
                    {{ $order->shipping_address['upazila'] ?? '' }}, {{ $order->shipping_address['district'] ?? '' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #475569;">Estimated Delivery:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ $estimated_delivery ?? '3-5 Business Days' }}</td>
            </tr>
        </table>
    </div>

    <x-slot:reference>
        Reference ID: ORD-{{ $order->order_number }}<br>
        Status: Order Confirmed<br>
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('M d, Y h:i A') }} (GMT+6)
    </x-slot>

    <x-slot:cta>
        <div style="text-align: center;">
            <a href="{{ route('customer.order.show', $order->order_number) }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; margin-right: 8px;">Track Order</a>
            <a href="{{ route('customer.order.invoice', $order->order_number) }}" style="display: inline-block; background-color: #ffffff; color: #0f172a; border: 1px solid #0f172a; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">Download Invoice</a>
        </div>
    </x-slot>

    <x-slot:nextStep>
        We'll keep you updated as your order progresses.<br>
        <span style="font-size: 13px; font-weight: normal; color: #475569; display: block; margin-top: 8px;">You’ll receive further notifications when your order is confirmed, shipped, out for delivery, & delivered.<br>If you did not place this order, please contact Garikothay Support immediately.</span>
    </x-slot>
</x-transactional-email>
