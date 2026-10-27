<?php
namespace App\Http\Controllers;
use App\Models\{Category, Product};
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function show(Request $request, Category $category) {
        $query = Product::active()
            ->where(function ($productsQuery) use ($category) {
                $productsQuery->where('category_id', $category->id)
                    ->orWhereHas('additionalCategories', fn ($categoriesQuery) => $categoriesQuery->whereKey($category->id));
            })
            ->with(['images', 'variants']);
        $effectivePriceSql = Product::effectivePriceSql();
        // Filters
        if ($request->filled('min_price')) {
          $query->whereRaw($effectivePriceSql . ' >= ?', [(float) $request->min_price]);
        }
        if ($request->filled('max_price')) {
          $query->whereRaw($effectivePriceSql . ' <= ?', [(float) $request->max_price]);
        }
        if ($request->filter === 'featured') $query->where('is_featured', true);
        if ($request->filter === 'bestseller') $query->where('is_bestseller', true);
        if ($request->filter === 'new') $query->where('is_new_arrival', true);
        // Sorting
        switch($request->sort) {
            case 'price_asc': $query->orderByRaw($effectivePriceSql . ' ASC'); break;
            case 'price_desc': $query->orderByRaw($effectivePriceSql . ' DESC'); break;
            case 'newest': $query->orderBy('created_at', 'desc'); break;
            case 'rating': $query->orderBy('rating', 'desc'); break;
            default: $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }
        $products = $query->paginate(16)->withQueryString();
        $subcategories = $category->children()->where('is_active', true)->get();
        $relatedCategories = Category::where('is_active', true)->whereNull('parent_id')->where('id', '!=', $category->id)->limit(10)->get();
        $wishlistedIds = auth()->check() ? auth()->user()->wishlist()->pluck('product_id')->all() : [];
        return view('category.show', compact('category','products','subcategories','relatedCategories','wishlistedIds'));
    }
}
