<x-mail::message>
# Hi {{ $cart->user->name ?? 'Guest' }},

You left some items in your cart! Don't miss out on completing your purchase.

<x-mail::table>
| Product       | Quantity         | Price  |
| :--------- | :------------- | :-------- |
@foreach($cart->items as $item)
| {{ $item->product->name ?? 'Product' }} | {{ $item->quantity }} | BDT {{ number_format($item->total, 2) }} |
@endforeach
</x-mail::table>

**Total Cart Value:** BDT {{ number_format($cart->subtotal, 2) }}

<x-mail::button :url="url('/cart')">
View Your Cart
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
