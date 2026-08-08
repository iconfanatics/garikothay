<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Customer Information</h3>
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                @if($cart->user)
                    <p><strong>Name:</strong> {{ $cart->user->name }}</p>
                    <p><strong>Email:</strong> {{ $cart->user->email }}</p>
                    <p><strong>Mobile Number:</strong> {{ $cart->user->phone ?? 'N/A' }}</p>
                @else
                    <p><strong>Guest User</strong></p>
                    <p><strong>Session ID:</strong> {{ substr($cart->session_id, 0, 8) }}...</p>
                @endif
                <p class="mt-2"><strong>Last Activity:</strong> {{ $cart->updated_at ? $cart->updated_at->format('d M Y, h:i A') : 'N/A' }}</p>
                <p><strong>Cart Abandoned At:</strong> {{ $cart->updated_at ? $cart->updated_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Recovery Information</h3>
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                <p>
                    <strong>Recovery Status:</strong> 
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                        @if($cart->recovery_status === 'Recovered') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @elseif($cart->recovery_status === 'Expired') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                        @endif">
                        {{ $cart->recovery_status ?? 'Pending' }}
                    </span>
                </p>
                <p class="mt-2"><strong>Reminder Sent:</strong> {{ $cart->is_reminder_sent ? 'Yes' : 'No' }}</p>
                @if($cart->is_reminder_sent)
                    <p><strong>Reminder Sent At:</strong> {{ $cart->reminder_sent_at ? $cart->reminder_sent_at->format('d M Y, h:i A') : 'N/A' }}</p>
                @endif
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Cart Items ({{ $cart->item_count }})</h3>
        <div class="mt-2 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Product</th>
                                <th scope="col" class="py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">SKU</th>
                                <th scope="col" class="py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Price</th>
                                <th scope="col" class="py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Qty</th>
                                <th scope="col" class="py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($cart->items as $item)
                            <tr>
                                <td class="whitespace-nowrap py-2 text-sm text-gray-900 dark:text-white">
                                    {{ $item->product ? $item->product->name : 'Deleted Product' }}
                                    @if($item->variant)
                                        <span class="text-xs text-gray-500 block">Variant: {{ $item->variant->name }}</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap py-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->product ? $item->product->sku : 'N/A' }}
                                    @if($item->variant && $item->variant->sku)
                                        <span class="text-xs block">Var: {{ $item->variant->sku }}</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap py-2 text-sm text-gray-500 dark:text-gray-400 text-right">৳{{ number_format($item->unit_price, 2) }}</td>
                                <td class="whitespace-nowrap py-2 text-sm text-gray-500 dark:text-gray-400 text-right">{{ $item->quantity }}</td>
                                <td class="whitespace-nowrap py-2 text-sm text-gray-900 dark:text-white text-right">৳{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row" colspan="4" class="hidden py-2 pl-4 pr-3 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Subtotal</th>
                                <th scope="row" class="py-2 pl-4 pr-3 text-left text-sm font-normal text-gray-500 sm:hidden">Subtotal</th>
                                <td class="py-2 pl-3 pr-4 text-right text-sm font-medium text-gray-900 dark:text-white sm:pr-0">৳{{ number_format($cart->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="4" class="hidden py-2 pl-4 pr-3 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Shipping Charge</th>
                                <th scope="row" class="py-2 pl-4 pr-3 text-left text-sm font-normal text-gray-500 sm:hidden">Shipping Charge</th>
                                <td class="py-2 pl-3 pr-4 text-right text-sm font-medium text-gray-900 dark:text-white sm:pr-0">৳{{ number_format($cart->shipping_charge ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="4" class="hidden py-2 pl-4 pr-3 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Discount</th>
                                <th scope="row" class="py-2 pl-4 pr-3 text-left text-sm font-normal text-gray-500 sm:hidden">Discount</th>
                                <td class="py-2 pl-3 pr-4 text-right text-sm font-medium text-gray-900 dark:text-white sm:pr-0 text-red-500">- ৳{{ number_format($cart->discount_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="4" class="hidden py-3 pl-4 pr-3 text-right text-sm font-bold text-gray-900 dark:text-white sm:table-cell sm:pl-0">Grand Total</th>
                                <th scope="row" class="py-3 pl-4 pr-3 text-left text-sm font-bold text-gray-900 dark:text-white sm:hidden">Grand Total</th>
                                @php
                                    $grandTotal = $cart->grand_total > 0 ? $cart->grand_total : ($cart->subtotal + ($cart->shipping_charge ?? 0) - ($cart->discount_amount ?? 0));
                                @endphp
                                <td class="py-3 pl-3 pr-4 text-right text-sm font-bold text-gray-900 dark:text-white sm:pr-0">৳{{ number_format(max(0, $grandTotal), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
