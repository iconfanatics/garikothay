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
    @php
        $allItems = collect();
        foreach($orders as $order) {
            foreach($order->items as $item) {
                $item->order_number_ref = $order->order_number;
                $item->order_date_ref = $order->created_at->format('M d, Y');
                $allItems->push($item);
            }
        }
        $itemsBySupplier = $allItems->groupBy(function($item) {
            return $item->product?->supplier_id ?? 'none';
        });
    @endphp

    @foreach($itemsBySupplier as $supplierId => $items)
        @php
            $supplier = $supplierId !== 'none' ? \App\Models\Supplier::find($supplierId) : null;
        @endphp
        <div class="invoice-box" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
            <div class="header">
                <h1>CONSOLIDATED VENDOR SLIP</h1>
                <p>{{ \App\Models\Setting::get('site_name', 'Garikothay') }}</p>
                <p><small>Generated on: {{ now()->format('M d, Y h:i A') }}</small></p>
            </div>

            <div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd;">
                <strong style="font-size: 18px;">Vendor: {{ $supplier ? $supplier->name : 'In-House / Own Stock' }}</strong><br>
                @if($supplier)
                    <div style="margin-top: 5px;">
                        <strong>Contact:</strong> {{ $supplier->contact_person ? $supplier->contact_person . ' (' . $supplier->contact_number . ')' : $supplier->contact_number }} <br>
                        <strong>Address:</strong> {{ $supplier->address }}
                    </div>
                @endif
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 25%;">Order Info</th>
                        <th style="width: 55%;">Product Details</th>
                        <th style="width: 15%;" class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->order_number_ref }}</strong><br>
                            <small>{{ $item->order_date_ref }}</small>
                        </td>
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
            
            <div style="text-align: right; margin-top: 20px;">
                <strong style="font-size: 16px;">Total Items to Supply: {{ $items->sum('quantity') }}</strong>
            </div>
            
            <div class="footer">
                {{ \App\Models\Setting::get('site_name', 'Garikothay') }} - Bulk Vendor Pick List
            </div>
        </div>
    @endforeach
</body>
</html>
