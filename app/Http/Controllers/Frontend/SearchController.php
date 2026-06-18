<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $searchInput = $request->query('q');
        $query = is_string($searchInput) ? trim($searchInput) : '';
        $results = collect();

        if (mb_strlen($query) >= 2) {
            $results = Product::with(['translations', 'images'])
                ->active()
                ->whereHas('translations', fn ($q) => $q->where('name', 'like', "%{$query}%"))
                ->paginate(16);
        }

        return view('search.index', compact('query', 'results'));
    }
}
