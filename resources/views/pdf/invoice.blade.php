<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { width: 100%; text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 28px; color: #111; }
        .invoice-details { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .invoice-details td { padding: 5px; vertical-align: top; }
        .status-badge { font-weight: bold; padding: 5px 10px; border-radius: 4px; display: inline-block; text-transform: uppercase; }
        
        .status-paid { background-color: #d1fae5; color: #065f46; }
        .status-unpaid { background-color: #fee2e2; color: #991b1b; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .items-table th { background-color: #f8f8f8; font-weight: bold; }
        .items-table .text-right { text-align: right; }
        .totals-table { width: 50%; float: right; border-collapse: collapse; }
        .totals-table th, .totals-table td { padding: 8px; text-align: right; border-bottom: 1px solid #eee; }
        .totals-table th { font-weight: bold; }
        .clear { clear: both; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>INVOICE</h1>
            <p>{{ \App\Models\Setting::get('site_name', 'Garikothay') }}</p>
        </div>

        @php
            $statusStr = strtolower($order->payment_status->value ?? 'unpaid');
            if (in_array($statusStr, ['partially_refunded', 'refunded'])) {
                $statusClass = 'status-unpaid';
            } elseif ($statusStr === 'paid') {
                $statusClass = 'status-paid';
            } else {
                $statusClass = 'status-pending';
            }
        @endphp

        <table class="invoice-details">
            <tr>
                <td style="width: 50%;">
                    <strong>Bill To:</strong><br>
                    {{ $order->user->name ?? 'Guest' }}<br>
                    {{ $order->user->email ?? '' }}<br>
                    {{ $order->billing_address['phone'] ?? $order->shipping_phone ?? $order->user->phone ?? '' }}<br>
                    {{ $order->billing_address['full_address'] ?? $order->shipping_full_address ?? '' }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Invoice #:</strong> {{ 'INV-' . $order->order_number }}<br>
                    <strong>Order #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Status:</strong> <span class="status-badge {{ $statusClass }}">{{ strtoupper($order->payment_status->label() ?? 'UNPAID') }}</span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if($item->variant_name)
                            <br><small>{{ $item->variant_name }}</small>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }} BDT</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2) }} BDT</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <th>Subtotal:</th>
                <td>{{ number_format($order->subtotal, 2) }} BDT</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <th>Discount:</th>
                <td>-{{ number_format($order->discount_amount, 2) }} BDT</td>
            </tr>
            @endif
            <tr>
                <th>Shipping:</th>
                <td>{{ number_format($order->shipping_amount, 2) }} BDT</td>
            </tr>
            <tr>
                <th>Tax:</th>
                <td>{{ number_format($order->tax_amount, 2) }} BDT</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td><strong>{{ number_format($order->total, 2) }} BDT</strong></td>
            </tr>
        </table>
        
        <div class="clear"></div>
        
        <div class="footer">
            Thank you for shopping with us!
        </div>
    </div>
</body>
</html>
