<x-mail::message>
# Order Status Update - #{{ $order->order_number }}

Hi {{ $order->user->name ?? 'Customer' }},

The status of your order **#{{ $order->order_number }}** has been updated to: **{{ ucfirst($order->status) }}**.

@if($order->status === 'shipped' || $order->status === 'delivered')
Good news! Your order is on its way or has arrived. 
@elseif($order->status === 'cancelled')
We're sorry, but your order has been cancelled. If you have already paid, your refund will be processed shortly.
@endif

<x-mail::button :url="route('login')">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
