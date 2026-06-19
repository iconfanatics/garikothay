<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly ShippingService $shippingService,
    ) {}

    public function index(): View
    {
        $cart = $this->cartService->getCart()->load(
            'items.product.images',
            'items.product.category.translations',
            'items.variant',
            'coupon',
        );
        $discount = $cart->coupon && $cart->coupon->isValid()
            ? $cart->coupon->calculateDiscount($cart->subtotal)
            : 0;
        $orderValue = max(0, $cart->subtotal - $discount);

        return view('cart.index', [
            'cart' => $cart,
            'shippingCharge' => $this->shippingService->isFreeShipping($orderValue)
                ? 0
                : $this->shippingService->calculate(''),
            'deliveryTime' => $this->shippingService->getDeliveryTime(),
            'deliveryPartner' => $this->shippingService->getDeliveryPartner(),
        ]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $item = $this->cartService->addItem(
            $request->integer('product_id'),
            $request->integer('quantity', 1),
            $request->integer('variant_id') ?: null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'cart_count' => $this->cartService->getItemCount(),
        ]);
    }

    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);

        $item = $this->cartService->updateItem($itemId, $request->integer('quantity'));

        return response()->json(['success' => true, 'item_total' => $item->total]);
    }

    public function removeItem(int $itemId): JsonResponse
    {
        $this->cartService->removeItem($itemId);

        return response()->json([
            'success' => true,
            'cart_count' => $this->cartService->getItemCount(),
        ]);
    }

    public function applyCoupon(ApplyCouponRequest $request): JsonResponse
    {
        $result = $this->cartService->applyCoupon($request->validated('code'));
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function removeCoupon(): JsonResponse
    {
        $this->cartService->removeCoupon();
        return response()->json(['success' => true]);
    }

    public function count(): JsonResponse
    {
        return response()->json(['count' => $this->cartService->getItemCount()]);
    }
}
