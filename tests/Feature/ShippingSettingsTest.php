<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\SettingType;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShippingSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_service_uses_merchant_logistics_settings(): void
    {
        $this->setSetting('dhaka_city_shipping_charge', '80', SettingType::Number);
        $this->setSetting('outside_dhaka_shipping_charge', '150', SettingType::Number);
        $this->setSetting('free_shipping_threshold', '0', SettingType::Number);
        $this->setSetting('delivery_time', '3-5 business days', SettingType::Text);
        $this->setSetting('delivery_partner', 'Pathao Courier', SettingType::Text);

        $shipping = app(ShippingService::class);

        $this->assertSame(80.0, $shipping->calculate('Dhaka', 'Dhaka'));
        $this->assertSame(80.0, $shipping->calculate('ঢাকা', 'ঢাকা'));
        $this->assertSame(150.0, $shipping->calculate('Dhaka', 'Savar'));
        $this->assertSame(150.0, $shipping->calculate('Chittagong', 'Chittagong'));
        $this->assertFalse($shipping->isFreeShipping(100000));
        $this->assertSame('3-5 business days', $shipping->getDeliveryTime());
        $this->assertSame('Pathao Courier', $shipping->getDeliveryPartner());
    }

    public function test_checkout_saves_shipping_charge_and_logistics_snapshot(): void
    {
        $this->setSetting('dhaka_city_shipping_charge', '80', SettingType::Number);
        $this->setSetting('outside_dhaka_shipping_charge', '150', SettingType::Number);
        $this->setSetting('free_shipping_threshold', '5000', SettingType::Number);
        $this->setSetting('delivery_time', '2-4 business days', SettingType::Text);
        $this->setSetting('delivery_partner', 'Steadfast', SettingType::Text);

        $user = User::factory()->create();
        $category = Category::create(['slug' => 'parts', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'slug' => 'engine-oil',
            'price' => 1200,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);
        $product->translations()->create([
            'locale' => 'en',
            'name' => 'Engine Oil',
        ]);
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1200,
        ]);
        $cart->load(['items.product.translations', 'coupon']);

        $order = app(CheckoutService::class)->placeOrder(
            $user,
            $cart,
            [
                'full_name' => 'Test Customer',
                'phone' => '01700000000',
                'address_line_1' => 'Dhaka',
                'city' => 'Dhaka',
                'district' => 'Dhaka',
                'division' => 'Dhaka',
            ],
            'cod',
        );

        $this->assertSame(80.0, (float) $order->shipping_amount);
        $this->assertSame(2480.0, (float) $order->total);
        $this->assertSame('2-4 business days', $order->delivery_time);
        $this->assertSame('Steadfast', $order->delivery_partner);
    }

    private function setSetting(string $key, string $value, SettingType $type): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'group' => 'logistics',
                'value' => $value,
                'type' => $type,
            ],
        );
        Cache::forget("setting:{$key}");
    }
}
