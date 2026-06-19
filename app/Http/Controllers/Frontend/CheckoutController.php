<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Payments\PaymentManager;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService,
        private readonly ShippingService $shippingService,
        private readonly PaymentManager $paymentManager,
    ) {}

    public function index(): View|RedirectResponse
    {
        if (! auth()->check() && ! $this->guestCheckoutEnabled()) {
            return redirect()->guest(route('login'));
        }

        $cart = $this->cartService->getCart()->load('items.product.images', 'coupon');
        $discount = $cart->coupon && $cart->coupon->isValid()
            ? $cart->coupon->calculateDiscount($cart->subtotal)
            : 0;
        $orderValue = max(0, $cart->subtotal - $discount);

        return view('checkout.index', [
            'cart' => $cart,
            'addresses' => auth()->user()?->addresses ?? collect(),
            'guestCheckoutEnabled' => $this->guestCheckoutEnabled(),
            'orderValue' => $orderValue,
            'freeShippingThreshold' => (float) Setting::get('free_shipping_threshold', 1500),
            'dhakaCityShippingCharge' => $this->shippingService->getDhakaCityCharge(),
            'outsideDhakaShippingCharge' => $this->shippingService->getOutsideDhakaCharge(),
            'deliveryTime' => $this->shippingService->getDeliveryTime(),
            'deliveryPartner' => $this->shippingService->getDeliveryPartner(),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        if (! auth()->check() && ! $this->guestCheckoutEnabled()) {
            return redirect()->guest(route('login'));
        }

        try {
            $cart = $this->cartService->getCart()->load('items.product');
            $data = $request->validated();
            $data['district'] = $data['city'];
            $data['division'] = $this->shippingService->isDhakaCity('Dhaka', $data['city'])
                ? 'Dhaka'
                : 'Outside Dhaka';
            $user = auth()->user() ?? $this->resolveGuestUser($data);

            if (auth()->check() && $request->boolean('save_address')) {
                $user->addresses()->create([
                    'label' => 'home',
                    'full_name' => $data['full_name'],
                    'phone' => $data['phone'],
                    'address_line_1' => $data['address_line_1'],
                    'address_line_2' => $data['address_line_2'] ?? null,
                    'city' => $data['city'],
                    'district' => $data['district'],
                    'division' => $data['division'],
                    'postal_code' => $data['postal_code'] ?? null,
                ]);
            }

            $order = $this->checkoutService->placeOrder(
                $user,
                $cart,
                $data,
                $data['payment_method'],
            );

            try {
                $paymentResult = $this->paymentManager->driver($order->payment_method->value)->initiate($order);

                $this->cartService->clear();

                return redirect($paymentResult['redirect']);
            } catch (\Throwable $e) {
                $this->checkoutService->rollbackOrder($order);
                throw $e;
            }
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(string $orderNumber): View
    {
        $orderQuery = Order::with(['items.product', 'payment'])
            ->where('order_number', $orderNumber);

        if (auth()->check()) {
            $orderQuery->where('user_id', auth()->id());
        }

        $order = $orderQuery->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    private function guestCheckoutEnabled(): bool
    {
        return (bool) Setting::get('guest_checkout_enabled', true);
    }

    private function resolveGuestUser(array $data): User
    {
        return User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['full_name'],
                'phone' => User::where('phone', $data['phone'])->doesntExist() ? $data['phone'] : null,
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'is_active' => true,
            ]
        );
    }
}
