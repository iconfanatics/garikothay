<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_page_accepts_a_missing_query(): void
    {
        $this->get('/search')
            ->assertOk()
            ->assertViewHas('query', '');
    }

    public function test_search_page_accepts_a_null_query_value(): void
    {
        $this->get('/search?q')
            ->assertOk()
            ->assertViewHas('query', '');
    }

    public function test_search_inputs_require_at_least_two_characters(): void
    {
        $this->get('/search')
            ->assertOk()
            ->assertSeeHtml('required minlength="2"')
            ->assertSeeHtml('name="category"')
            ->assertSee('Please type something to search.');
    }

    public function test_search_results_page_does_not_render_an_extra_search_form(): void
    {
        $response = $this->get('/search?q=k')->assertOk();

        $this->assertSame(2, substr_count($response->getContent(), 'name="q"'));
    }

    public function test_search_results_are_limited_to_the_selected_category(): void
    {
        $keyboards = $this->createCategory('keyboards', 'Keyboards');
        $mice = $this->createCategory('mice', 'Mice');

        $this->createProduct($keyboards, 'keyboard-pro', 'Keyboard Pro');
        $this->createProduct($mice, 'keyboard-mouse-combo', 'Keyboard Mouse Combo');

        $this->get('/search?q=keyboard&category=keyboards')
            ->assertOk()
            ->assertSee('Keyboard Pro')
            ->assertDontSee('Keyboard Mouse Combo')
            ->assertSee('Keyboards');
    }

    private function createCategory(string $slug, string $name): Category
    {
        $category = Category::create([
            'slug' => $slug,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $category->translations()->create([
            'locale' => 'en',
            'name' => $name,
        ]);

        return $category;
    }

    private function createProduct(Category $category, string $slug, string $name): void
    {
        $product = Product::create([
            'category_id' => $category->id,
            'slug' => $slug,
            'sku' => strtoupper($slug),
            'price' => 1000,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $product->translations()->create([
            'locale' => 'en',
            'name' => $name,
        ]);
    }
}
