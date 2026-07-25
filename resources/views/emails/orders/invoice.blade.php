<x-mail::message>
# Order Confirmation - #{{ $order->order_number }}

Hi {{ $order->user->name ?? 'Customer' }},

Thank you for your order! We've received it and will start processing it right away.

**Order Details:**
- **Order ID:** {{ $order->order_number }}
- **Date:** {{ $order->created_at->format('d M Y, h:i A') }}
- **Status:** {{ ucfirst($order->status) }}
- **Payment Method:** {{ ucfirst($order->payment_method) }}

<x-mail::table>
| Item       | Qty         | Price  |
| ------------- |:-------------:| --------:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ৳{{ number_format($item->unit_price, 2) }} |
@endforeach
| | **Subtotal** | **৳{{ number_format($order->subtotal, 2) }}** |
@if($order->shipping_cost > 0)
| | **Shipping** | **৳{{ number_format($order->shipping_cost, 2) }}** |
@endif
@if($order->discount > 0)
| | **Discount** | **-৳{{ number_format($order->discount, 2) }}** |
@endif
| | **Total** | **৳{{ number_format($order->total, 2) }}** |
</x-mail::table>

**Shipping Address:**
{{ $order->shipping_address['address'] ?? 'N/A' }}
<br>
{{ $order->shipping_address['district'] ?? '' }}, {{ $order->shipping_address['division'] ?? '' }}
<br>
Phone: {{ $order->shipping_address['phone'] ?? 'N/A' }}

<x-mail::button :url="route('login')">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
