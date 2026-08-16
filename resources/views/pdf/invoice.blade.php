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
        @php
            $hasInvoice = isset($invoice) && $invoice;
            $paymentStatusRaw = $hasInvoice && $invoice->payment_status ? $invoice->payment_status : ($order->payment_status ?? 'unpaid');
            $statusStr = is_string($paymentStatusRaw) ? strtolower($paymentStatusRaw) : (isset($paymentStatusRaw->value) ? strtolower($paymentStatusRaw->value) : strtolower((string)$paymentStatusRaw));
            if (in_array($statusStr, ['partially_refunded', 'refunded'])) {
                $statusClass = 'status-unpaid';
            } elseif ($statusStr === 'paid') {
                $statusClass = 'status-paid';
            } else {
                $statusClass = 'status-pending';
            }
            $paymentStatus = strtoupper(str_replace('_', ' ', $statusStr));
            $dueDate = $hasInvoice && $invoice->due_date ? (\Carbon\Carbon::parse($invoice->due_date)->format('M d, Y')) : null;
            $transactionId = $hasInvoice && $invoice->transaction_id ? $invoice->transaction_id : null;
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
                    <strong>Invoice #:</strong> {{ $hasInvoice ? $invoice->invoice_number : ('INV-' . $order->order_number) }}<br>
                    <strong>Order #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $hasInvoice ? $invoice->invoice_date->format('M d, Y') : $order->created_at->format('M d, Y') }}
                    @if($dueDate)
                        <br><strong>Due Date:</strong> {{ $dueDate }}
                    @endif
                    @if($transactionId)
                        <br><strong>Transaction ID:</strong> {{ $transactionId }}
                    @endif
                    <div style="margin-top: 8px;">
                        <strong>Status:</strong> <span class="status-badge {{ $statusClass }}">{{ $paymentStatus }}</span>
                    </div>
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
                <td>{{ number_format($hasInvoice ? $invoice->subtotal : $order->subtotal, 2) }} BDT</td>
            </tr>
            @php
                $discountAmount = $hasInvoice ? $invoice->discount_amount : $order->discount_amount;
                $shippingAmount = $hasInvoice ? $invoice->shipping_amount : $order->shipping_amount;
                $taxAmount = $hasInvoice ? $invoice->tax_amount : $order->tax_amount;
                $totalAmount = $hasInvoice ? $invoice->total : $order->total;
                $paidAmount = ($hasInvoice && $invoice->paid_amount > 0) ? $invoice->paid_amount : ($statusStr === 'paid' ? $totalAmount : 0);
                $dueAmount = max(0, $totalAmount - $paidAmount);
            @endphp
            @if($discountAmount > 0)
            <tr>
                <th>Discount:</th>
                <td>-{{ number_format($discountAmount, 2) }} BDT</td>
            </tr>
            @endif
            <tr>
                <th>Shipping:</th>
                <td>{{ number_format($shippingAmount, 2) }} BDT</td>
            </tr>
            <tr>
                <th>Tax:</th>
                <td>{{ number_format($taxAmount, 2) }} BDT</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td><strong>{{ number_format($totalAmount, 2) }} BDT</strong></td>
            </tr>
            <tr>
                <th>Paid Amount:</th>
                <td>{{ number_format($paidAmount, 2) }} BDT</td>
            </tr>
            <tr>
                <th>Due Amount:</th>
                <td>{{ number_format($dueAmount, 2) }} BDT</td>
            </tr>
        </table>
        
        <div class="clear"></div>
        
        @if($hasInvoice && $invoice->customer_note)
        <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-left: 4px solid #111;">
            <strong>Note:</strong><br>
            {{ $invoice->customer_note }}
        </div>
        @endif
        
        <div class="clear"></div>
        
        <div class="footer">
            Thank you for shopping with us!
        </div>
    </div>
</body>
</html>
