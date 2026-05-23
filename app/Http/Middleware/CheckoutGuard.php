<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\CartService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutGuard
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        return $next($request);
    }
}
