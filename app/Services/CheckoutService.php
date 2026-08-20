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

            if ($coupon && $coupon->isValid($user)) {
                $discount = $coupon->calculateDiscount($cart);
                $this->couponService->incrementUsage($coupon);
            }

            $shippingMethodId = $shippingAddress['shipping_method_id'] ?? null;
            $shippingMethod = $shippingMethodId ? \App\Models\ShippingMethod::find($shippingMethodId) : null;

            if ($shippingMethod) {
                if ($shippingMethod->free_shipping_enabled && ($shippingMethod->free_shipping_threshold <= 0 || ($subtotal - $discount) >= $shippingMethod->free_shipping_threshold)) {
                    $shipping = 0;
                } else {
                    $shipping = $shippingMethod->base_charge;
                }
            } else {
                $shipping = $this->shippingService->isFreeShipping($subtotal - $discount)
                    ? 0
                    : $this->shippingService->calculate(
                        $shippingAddress['division'] ?? '',
                        $shippingAddress['city'] ?? null,
                    );
            }

            $total = max(0, $subtotal - $discount + $shipping);
            $shippingAddress['delivery_time'] = $this->shippingService->getDeliveryTime();
            $shippingAddress['delivery_partner'] = $this->shippingService->getDeliveryPartner();

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_amount' => $shipping,
                'shipping_method_id' => $shippingMethodId,
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

                $this->productRepository->updateStock(
                    (int) $item->product_id,
                    (int) $item->quantity,
                    true,
                );
            }

            event(new OrderPlaced($order));

            return $order;
        });
    }

    public function rollbackOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $this->productRepository->updateStock(
                    (int) $item->product_id,
                    (int) $item->quantity,
                    false,
                );
            }
            $order->delete();
        });
    }

    private function validateStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            if (!$item->product->isInStock() && !$item->product->is_preorder) {
                throw new \RuntimeException(
                    "Product is unavailable: {$item->product->name}"
                );
            }

            if (!$item->product->is_preorder && $item->product->stock_quantity < $item->quantity) {
                throw new \RuntimeException(
                    "Insufficient stock for: {$item->product->name}"
                );
            }
        }
    }
}
