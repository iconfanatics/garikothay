<x-transactional-email>
    <x-slot:purpose>⭐ Share Your Experience</x-slot:purpose>
    <x-slot:greeting>Hello, {{ $order->user->name ?? 'Customer' }}! 👋</x-slot:greeting>

    We hope you are enjoying your recent purchase from Garikothay. We'd love to hear your thoughts!

    Your feedback helps us improve and helps other customers make better decisions.

    <x-slot:reference>
        Reference ID: {{ $order->order_number }}
        Status: Delivered
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (GMT+6)
    </x-slot:reference>

    <x-slot:cta>
        <x-mail::button :url="route('customer.orders.show', $order)">
            Write a Review
        </x-mail::button>
    </x-slot:cta>

    <x-slot:nextStep>
        If the button above doesn't work, log into your account and navigate to My Orders.
    </x-slot:nextStep>
</x-transactional-email>
