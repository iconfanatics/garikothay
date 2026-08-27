<x-transactional-email>
    <x-slot:purpose>🛒 Complete Your Purchase</x-slot:purpose>
    <x-slot:greeting>Hello, {{ $cart->user->name ?? 'Guest' }}! 👋</x-slot:greeting>

    You left some items in your cart! Don't miss out on completing your purchase.

    <x-mail::table>
    | Product       | Quantity         | Price  |
    | :--------- | :------------- | :-------- |
    @foreach($cart->items as $item)
    | {{ $item->product->name ?? 'Product' }} | {{ $item->quantity }} | BDT {{ number_format($item->total, 2) }} |
    @endforeach
    </x-mail::table>

    **Total Cart Value:** BDT {{ number_format($cart->subtotal, 2) }}

    <x-slot:reference>
        Reference ID: CART-{{ $cart->id }}
        Status: Pending
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (GMT+6)
    </x-slot:reference>

    <x-slot:cta>
        <x-mail::button :url="url('/cart')">
            View Your Cart
        </x-mail::button>
    </x-slot:cta>

    <x-slot:nextStep>
        Your items are waiting for you!
    </x-slot:nextStep>
</x-transactional-email>
