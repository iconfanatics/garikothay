<div class="space-y-4">
    <div>
        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Customer Information</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            @if($cart->user)
                Name: {{ $cart->user->name }} <br>
                Email: {{ $cart->user->email }} <br>
                Phone: {{ $cart->user->phone ?? 'N/A' }}
            @else
                Guest User (Session ID: {{ substr($cart->session_id, 0, 8) }}...)
            @endif
        </p>
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
                                        <span class="text-xs text-gray-500 block">{{ $item->variant->name }}</span>
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
                                <th scope="row" colspan="3" class="hidden py-3 pl-4 pr-3 text-right text-sm font-normal text-gray-500 sm:table-cell sm:pl-0">Subtotal</th>
                                <th scope="row" class="py-3 pl-4 pr-3 text-left text-sm font-normal text-gray-500 sm:hidden">Subtotal</th>
                                <td class="py-3 pl-3 pr-4 text-right text-sm font-medium text-gray-900 dark:text-white sm:pr-0">৳{{ number_format($cart->subtotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
