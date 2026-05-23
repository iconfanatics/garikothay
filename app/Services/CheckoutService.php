<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ShippingService $shippingService,
        private readonly CouponService $couponService,
    ) {}

    public function placeOrder(
        User $user,
        Cart $cart,
        array $shippingAddress,
        string $paymentMethod,
    ): Order {
        $this->validateStock($cart);

        return DB::transaction(function () use ($user, $cart, $shippingAddress, $paymentMethod) {
            $subtotal = $cart->subtotal;
            $discount = 0;
            $coupon = $cart->coupon;

            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
                $this->couponService->incrementUsage($coupon);
            }

            $shipping = $this->shippingService->isFreeShipping($subtotal - $discount)
                ? 0
                : $this->shippingService->calculate($shippingAddress['division'] ?? 'Dhaka');

            $total = max(0, $subtotal - $discount + $shipping);

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_amount' => $shipping,
                'tax_amount' => 0,
                'total' => $total,
                'coupon_id' => $coupon?->id,
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->variant?->sku ?? $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->unit_price * $item->quantity,
                ]);

                $this->productRepository->updateStock($item->product_id, $item->quantity, true);
            }

            event(new OrderPlaced($order));

            return $order;
        });
    }

    public function rollbackOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $this->productRepository->updateStock($item->product_id, $item->quantity, false);
            }
            $order->delete();
        });
    }

    private function validateStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                throw new \RuntimeException(
                    "Insufficient stock for: {$item->product->name}"
                );
            }
        }
    }
}
