<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartStaleItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_removes_items_whose_products_were_deleted(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'slug' => 'parts',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'slug' => 'deleted-product',
            'sku' => 'DELETED-PRODUCT',
            'price' => 1500,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);
        $cart = Cart::create(['user_id' => $user->id]);
        $item = $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 1500,
        ]);

        $product->delete();

        $this->actingAs($user)
            ->get('/cart')
            ->assertOk()
            ->assertSee(__('general.your_cart_is_empty'));

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }
}
