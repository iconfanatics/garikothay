@if($orders->count())
    <table class="gk-account-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td style="font-weight:800;">{{ $order->order_number }}</td>
                    <td style="color:#6b7280;">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>{{ $order->items_count ?? $order->items->count() }}</td>
                    <td style="font-weight:800;">৳{{ number_format($order->total, 0) }}</td>
                    <td>
                        <span class="gk-badge {{ $statusClass($order) }}">{{ $order->status->label() }}</span>
                    </td>
                    <td style="text-align:right;">
                        <a href="{{ route('customer.order.show', $order->order_number) }}" class="gk-account-btn">👁 View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="gk-empty">
        <div class="gk-empty-icon">📦</div>
        <div class="gk-empty-title">No orders yet.</div>
        <p class="gk-empty-text">Your order history will appear here after you place your first order.</p>
        <a href="{{ route('shop.index') }}" class="gk-account-btn gk-account-btn-primary" style="margin-top:1rem;">Start Shopping</a>
    </div>
@endif
