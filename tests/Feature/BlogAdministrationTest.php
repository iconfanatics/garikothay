<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\BlogResource\Pages\CreateBlog;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class BlogAdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_published_blog_with_a_featured_image(): void
    {
        Storage::fake('public');

        $admin = Admin::create([
            'name' => 'Site Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $category = BlogCategory::create(['slug' => 'guides']);
        $category->setTranslation('en', ['name' => 'Guides']);

        $this->actingAs($admin, 'admin');

        Livewire::test(CreateBlog::class)
            ->fillForm([
                'translations' => [
                    'en' => [
                        'title' => 'How to Maintain Your Car',
                        'excerpt' => 'A practical maintenance guide.',
                        'content' => '<p>Check fluids and tyres regularly.</p>',
                    ],
                ],
                'slug' => 'how-to-maintain-your-car',
                'blog_category_id' => $category->id,
                'featured_image' => UploadedFile::fake()->image('car-guide.jpg', 1600, 900),
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $blog = Blog::with('translations')->firstOrFail();

        $this->assertSame($admin->id, $blog->author_id);
        $this->assertSame('How to Maintain Your Car', $blog->getTranslation('title', 'en'));
        $this->assertNotNull($blog->published_at);
        $this->assertNotNull($blog->featured_image);
        Storage::disk('public')->assertExists($blog->featured_image);
    }
}
