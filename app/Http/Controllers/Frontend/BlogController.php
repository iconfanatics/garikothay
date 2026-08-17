<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Blog::with(['translations', 'author', 'category'])->published()->latest();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
        }

        return view('blog.index', [
            'blogs' => $query->paginate(12),
            'categories' => BlogCategory::with('translations')->withCount('blogs')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $blog = Blog::with(['translations', 'author', 'category.translations', 'comments' => function ($query) {
            $query->where('is_approved', true)->latest();
        }])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('blog.show', compact('blog'));
    }

    public function comment(Request $request, Blog $blog)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $blog->comments()->create([
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'status' => 'Pending',
            'is_approved' => false,
        ]);

        return back()->with('success', __('general.comment_submitted_for_approval'));
    }
}
