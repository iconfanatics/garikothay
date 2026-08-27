<x-transactional-email>
    <x-slot:purpose>🟢 Order Status Update</x-slot:purpose>
    <x-slot:greeting>Hello, {{ $order->user->name ?? 'Customer' }}! 👋</x-slot:greeting>

    The status of your order **#{{ $order->order_number }}** has been updated to: **{{ $order->status->label() }}**.

    @if($order->status->value === 'shipped' || $order->status->value === 'delivered')
    Good news! Your order is on its way or has arrived.
    @elseif($order->status->value === 'cancelled')
    We're sorry, but your order has been cancelled. If you have already paid, your refund will be processed shortly.
    @endif

    <x-slot:reference>
        Reference ID: {{ $order->order_number }}
        Status: {{ $order->status->label() }}
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (GMT+6)
    </x-slot:reference>

    <x-slot:cta>
        <x-mail::button :url="route('login')">
            Track Order
        </x-mail::button>
    </x-slot:cta>

    <x-slot:nextStep>
        We'll keep you updated as your order progresses.
    </x-slot:nextStep>
</x-transactional-email>
