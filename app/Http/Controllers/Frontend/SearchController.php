<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $searchInput = $request->query('q');
        $query = is_string($searchInput) ? trim($searchInput) : '';
        $categoryInput = $request->query('category');
        $categorySlug = is_string($categoryInput) ? trim($categoryInput) : '';
        $selectedCategory = $categorySlug !== ''
            ? Category::query()->with('children:id,parent_id')->active()->where('slug', $categorySlug)->first()
            : null;
        $results = collect();

        if (mb_strlen($query) >= 2) {
            $productQuery = Product::with(['translations', 'images', 'category.translations'])
                ->active()
                ->where(function($q) use ($query) {
                    $q->whereHas('translations', fn ($tq) => $tq->where('name', 'like', "%{$query}%"))
                      ->orWhere('sku', 'like', "%{$query}%")
                      ->orWhereHas('variants', fn ($vq) => $vq->where('sku', 'like', "%{$query}%"));
                });

            if ($selectedCategory) {
                $categoryIds = $selectedCategory->children
                    ->pluck('id')
                    ->prepend($selectedCategory->id)
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $productQuery->whereIn('category_id', $categoryIds);
            }

            $results = $productQuery->paginate(16);
        }

        return view('search.index', compact('query', 'results', 'categorySlug', 'selectedCategory'));
    }
}
