<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getTree(): Collection
    {
        return Category::with(['translations', 'children.translations'])
            ->whereNull('parent_id')
            ->active()
            ->orderBy('sort_order')
            ->get();
    }

    public function getActive(): Collection
    {
        return Category::with(['translations'])
            ->active()
            ->orderBy('sort_order')
            ->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::with(['translations', 'children.translations', 'parent.translations'])
            ->where('slug', $slug)
            ->first();
    }

    public function findById(int $id): ?Category
    {
        return Category::with(['translations', 'children.translations'])
            ->find($id);
    }

    public function getWithProductCount(): Collection
    {
        return Category::with(['translations', 'children.translations'])
            ->whereNull('parent_id')
            ->withCount('products')
            ->active()
            ->orderBy('sort_order')
            ->get();
    }
}
