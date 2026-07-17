<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Slip - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px dashed #aaa; }
        .header { width: 100%; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #111; letter-spacing: 2px; text-transform: uppercase; }
        .invoice-details { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .invoice-details td { padding: 5px; vertical-align: top; }
        .status-badge { font-weight: bold; padding: 5px 10px; border-radius: 4px; display: inline-block; text-transform: uppercase; background-color: #eee; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .items-table th { background-color: #f8f8f8; font-weight: bold; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        
        .cod-box { float: right; border: 2px solid #333; padding: 15px; width: 250px; text-align: center; background-color: #f9f9f9; }
        .cod-box h3 { margin: 0 0 10px 0; color: #d32f2f; font-size: 18px; }
        .cod-box p { margin: 0; font-size: 24px; font-weight: bold; }
        
        .clear { clear: both; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>PACKING SLIP / VENDOR COPY</h1>
            <p>{{ \App\Models\Setting::get('site_name', 'Garikothay') }}</p>
        </div>

        <table class="invoice-details">
            <tr>
                <td style="width: 50%;">
                    <strong>Deliver To (Customer):</strong><br>
                    <span style="font-size: 16px; font-weight: bold;">{{ $order->user->name ?? 'Guest' }}</span><br>
                    <strong>Phone:</strong> {{ $order->billing_address['phone'] ?? $order->shipping_phone ?? $order->user->phone ?? '' }}<br>
                    <strong>Address:</strong> {{ $order->billing_address['full_address'] ?? $order->shipping_full_address ?? '' }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Order #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Payment Method:</strong> <span class="status-badge">{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;" class="text-center">#</th>
                    <th style="width: 70%;">Product Details</th>
                    <th style="width: 20%;" class="text-center">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <br><small>Variant: {{ $item->variant_name }}</small>
                        @endif
                    </td>
                    <td class="text-center" style="font-size: 16px; font-weight: bold;">{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cod-box">
            <h3>COLLECT FROM CUSTOMER (COD)</h3>
            <p>{{ number_format($order->total, 2) }} BDT</p>
            @if(strtolower($order->payment_status->value ?? '') === 'paid')
                <p style="color: green; font-size: 16px; margin-top: 5px;">(ALREADY PAID)</p>
            @endif
        </div>
        
        <div class="clear"></div>
        
        <div class="footer">
            Generated on {{ now()->format('M d, Y h:i A') }}
        </div>
    </div>
</body>
</html>
