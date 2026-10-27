<?php
namespace App\Http\Controllers;
use App\Models\{Product, AddOn, Review};
use Illuminate\Http\Request;

class ProductController extends Controller {

    public function index(Request $request)
       { $query = Product::active()
             ->with(['images', 'category', 'variants']);
         // Search products
         if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
        }
       // Filter by category
       if ($request->filled('category')) {
           $query->where(function ($categoryQuery) use ($request) {
               $categoryQuery->whereHas('category', fn ($q) => $q->where('slug', $request->category))
                   ->orWhereHas('additionalCategories', fn ($q) => $q->where('slug', $request->category));
           });
       }
       // Sorting
       if ($request->sort === 'price_low') {
           $query->orderBy('price', 'asc');} elseif ($request->sort === 'price_high') { $query->orderBy('price', 'desc');} else { $query->latest();}
           $products = $query->paginate(12)->withQueryString();
           $categories = \App\Models\Category::all();
           $wishlistedIds = auth()->check()
               ? auth()->user()->wishlist()->pluck('product_id')->all()
               : [];
       return view('products.index', compact('products', 'categories', 'wishlistedIds'));
    }



    public function show(Product $product) {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'images', 'variants', 'occasions', 'reviews.user']);
        $addOns = AddOn::where('is_active', true)->orderBy('name')->get();
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images', 'variants'])
            ->limit(8)
            ->get();

        $isWishlisted = false;
        $reviewOrders = collect();
        if (auth()->check()) {
            $isWishlisted = $product->wishlists()->where('user_id', auth()->id())->exists();
            $reviewOrders = auth()->user()->orders()
                ->whereHas('items', fn ($query) => $query->where('product_id', $product->id))
                ->latest()
                ->get(['orders.id', 'orders.order_number']);
        }

        return view('products.show', compact('product', 'addOns', 'relatedProducts', 'isWishlisted', 'reviewOrders'));
    }



    
}
