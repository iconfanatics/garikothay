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

    public function test_supplier_and_operations_information_is_saved_and_stays_internal(): void
    {
        $product = Product::create([
            'category_id' => $this->createCategory()->id,
            'slug' => 'supplier-managed-headlight',
            'price' => 5000,
            'cost_price' => 3500,
            'minimum_selling_price' => 4200,
            'stock_quantity' => 5,
            'supplier_name' => 'Internal Parts Supplier',
            'supplier_contact_person' => 'Reference Person',
            'supplier_contact_number' => '01700000000',
            'supplier_address' => 'Internal supplier address',
            'supplier_stock_status' => 'in_stock',
            'supplier_stock_updated_at' => '2026-06-19',
            'product_source_url' => 'https://supplier.example/product/123',
            'supplier_product_code' => 'SUP-123',
            'has_return_support' => true,
            'is_authentic_product' => true,
            'supplier_shipping_charge' => 100,
            'supplier_delivery_time' => '2-3 business days',
            'supplier_delivery_partner' => 'Steadfast',
            'warranty_type' => 'supplier',
            'warranty_duration' => '6 months',
            'warranty_claim_process' => 'Contact the supplier with the invoice.',
            'internal_notes' => 'Secret commission and payment terms.',
            'is_active' => true,
        ]);
        $product->translations()->create([
            'locale' => 'en',
            'name' => 'Supplier Managed Headlight',
        ]);

        $this->assertSame(1500.0, $product->profit_margin);
        $this->assertSame(30.0, $product->profit_margin_percentage);
        $this->assertTrue($product->has_return_support);
        $this->assertTrue($product->is_authentic_product);

        $customerFacingViews = implode("\n", [
            file_get_contents(resource_path('views/shop/show.blade.php')),
            file_get_contents(resource_path('views/components/product-card.blade.php')),
            file_get_contents(resource_path('views/home.blade.php')),
        ]);

        $this->assertStringNotContainsString('supplier_name', $customerFacingViews);
        $this->assertStringNotContainsString('internal_notes', $customerFacingViews);
    }

    private function createCategory(): Category
    {
        return Category::create([
            'slug' => 'automotive-parts-' . fake()->unique()->numerify('###'),
            'is_active' => true,
        ]);
    }
}
