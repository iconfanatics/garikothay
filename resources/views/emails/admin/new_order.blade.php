<x-transactional-email>
    <x-slot:purpose>🔔 New Order Received</x-slot:purpose>
    <x-slot:greeting>Hello, Admin! 👋</x-slot:greeting>

    A new order has been placed on {{ config('app.name') }}.

    **Order Details:**
    - **Customer:** {{ $order->user->name ?? 'Guest' }} ({{ $order->user->phone ?? 'N/A' }})
    - **Total Amount:** ৳{{ number_format($order->total, 2) }}
    - **Payment Method:** {{ $order->payment_method->label() }}

    Please check the admin panel to process this order.

    <x-slot:reference>
        Reference ID: {{ $order->order_number }}
        Status: {{ $order->status->label() }}
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (GMT+6)
    </x-slot:reference>

    <x-slot:cta>
        <x-mail::button :url="route('filament.admin.resources.orders.edit', $order)">
            View Order in Admin Panel
        </x-mail::button>
    </x-slot:cta>
</x-transactional-email>
