<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Slip - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #000; margin: 0; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #000; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 26px; text-transform: uppercase; font-weight: bold; }
        .header h2 { margin: 5px 0 10px 0; font-size: 18px; font-weight: normal; }
        .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        
        .section-title { font-size: 16px; font-weight: bold; background-color: #f0f0f0; padding: 5px 10px; margin-top: 20px; border: 1px solid #000; border-bottom: none; }
        .section-content { border: 1px solid #000; padding: 10px; margin-bottom: 20px; }
        
        table.info-table { width: 100%; border-collapse: collapse; }
        table.info-table td { padding: 5px 0; vertical-align: top; }
        table.info-table td:first-child { width: 150px; font-weight: bold; }

        .items-table { width: 100%; border-collapse: collapse; border: 1px solid #000; }
        .items-table th, .items-table td { padding: 10px; border: 1px solid #000; text-align: left; }
        .items-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .items-table .text-center { text-align: center; }

        .vendor-note { border: 2px dashed #000; padding: 15px; text-align: center; font-size: 18px; font-weight: bold; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>VENDOR SLIP</h1>
            <h2>Seller Copy</h2>
            <div class="logo">{{ \App\Models\Setting::get('site_name', 'Garikothay.com') }}</div>
        </div>

        <div class="section-title">Order Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Order ID:</td>
                    <td>{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td>Order Date:</td>
                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">Product Details</div>
        <div class="section-content" style="padding: 0; border: none;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 60%; text-align: left;">Product Name</th>
                        <th style="width: 20%;">SKU</th>
                        <th style="width: 15%;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $item->product_name }}
                            @if($item->variant_name)
                                <br><small>Variant: {{ $item->variant_name }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->product_sku ?? 'N/A' }}</td>
                        <td class="text-center" style="font-size: 16px; font-weight: bold;">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section-title">Customer Delivery Info</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Customer Name:</td>
                    <td>{{ $order->shipping_address['full_name'] ?? $order->user->name ?? 'Guest' }}</td>
                </tr>
                <tr>
                    <td>Mobile Number:</td>
                    <td>{{ $order->shipping_address['phone'] ?? $order->user->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Delivery Address:</td>
                    <td>
                        {{ $order->shipping_address['address_line_1'] ?? $order->shipping_full_address ?? 'N/A' }}<br>
                        @if(isset($order->shipping_address['upazila'])) {{ $order->shipping_address['upazila'] }}, @endif
                        @if(isset($order->shipping_address['city'])) {{ $order->shipping_address['city'] }}, @endif
                        @if(isset($order->shipping_address['division'])) {{ $order->shipping_address['division'] }} @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title">Dispatch Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Shipping Method:</td>
                    <td>{{ $order->delivery_method ?? 'Steadfast Courier' }}</td>
                </tr>
                <tr>
                    <td>Tracking ID:</td>
                    <td>{{ $order->tracking_number ?? $order->steadfast_tracking_code ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="vendor-note">
            Vendor Note<br>
            <span style="font-size: 24px; color: #d32f2f;">Check Product Before Dispatch</span>
        </div>
    </div>
</body>
</html>
