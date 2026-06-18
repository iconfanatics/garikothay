<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductAdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sku_is_generated_when_it_is_not_provided(): void
    {
        $category = $this->createCategory();
        $product = Product::create([
            'category_id' => $category->id,
            'slug' => 'brake-pad',
            'price' => 2500,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);
        $secondProduct = Product::create([
            'category_id' => $category->id,
            'slug' => 'brake-disc',
            'price' => 3000,
            'stock_quantity' => 4,
            'is_active' => true,
        ]);

        $this->assertMatchesRegularExpression('/^GK-[A-Za-z0-9]{6}$/', $product->sku);
        $this->assertNotSame($product->sku, $secondProduct->sku);
    }

    public function test_product_images_are_synced_in_display_order(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/old.jpg', 'old');
        Storage::disk('public')->put('products/front.jpg', 'front');
        Storage::disk('public')->put('products/side.jpg', 'side');

        $product = Product::create([
            'category_id' => $this->createCategory()->id,
            'slug' => 'headlight',
            'price' => 4000,
            'stock_quantity' => 3,
            'is_active' => true,
        ]);
        $product->images()->create([
            'path' => 'products/old.jpg',
            'sort_order' => 0,
            'is_primary' => true,
        ]);

        $product->syncImages([
            'products/front.jpg',
            'products/side.jpg',
        ]);

        $this->assertDatabaseMissing('product_images', ['path' => 'products/old.jpg']);
        $this->assertDatabaseHas('product_images', [
            'path' => 'products/front.jpg',
            'sort_order' => 0,
            'is_primary' => true,
        ]);
        $this->assertDatabaseHas('product_images', [
            'path' => 'products/side.jpg',
            'sort_order' => 1,
            'is_primary' => false,
        ]);
        Storage::disk('public')->assertMissing('products/old.jpg');
    }

    private function createCategory(): Category
    {
        return Category::create([
            'slug' => 'automotive-parts-' . fake()->unique()->numerify('###'),
            'is_active' => true,
        ]);
    }
}
