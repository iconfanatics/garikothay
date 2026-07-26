<x-mail::message>
# New Order Received - #{{ $order->order_number }}

A new order has been placed on {{ config('app.name') }}.

**Order Details:**
- **Order ID:** {{ $order->order_number }}
- **Customer:** {{ $order->user->name ?? 'Guest' }} ({{ $order->user->phone ?? 'N/A' }})
- **Total Amount:** ৳{{ number_format($order->total, 2) }}
- **Payment Method:** {{ $order->payment_method->label() }}

Please check the admin panel to process this order.

<x-mail::button :url="route('filament.admin.resources.orders.edit', $order)">
View Order in Admin Panel
</x-mail::button>

Thanks,<br>
System Notification
</x-mail::message>
