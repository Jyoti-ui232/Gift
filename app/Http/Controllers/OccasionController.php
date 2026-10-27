<?php
namespace App\Http\Controllers;
use App\Models\{Occasion, Product};
use Illuminate\Http\Request;

class OccasionController extends Controller {
    public function show(Request $request, Occasion $occasion) {
        $query = $occasion->products()->where('is_active', true)->with(['images', 'variants']);
        $effectivePriceSql = Product::effectivePriceSql();

        if ($request->filled('min_price')) {
            $query->whereRaw($effectivePriceSql . ' >= CAST(? AS DECIMAL(10, 2))', [(float) $request->query('min_price')]);
        }
        if ($request->filled('max_price')) {
            $query->whereRaw($effectivePriceSql . ' <= CAST(? AS DECIMAL(10, 2))', [(float) $request->query('max_price')]);
        }
        if ($request->filter === 'featured') $query->where('is_featured', true);
        if ($request->filter === 'bestseller') $query->where('is_bestseller', true);
        if ($request->filter === 'new') $query->where('is_new_arrival', true);

        switch($request->sort) {
            case 'price_asc': $query->orderByRaw($effectivePriceSql . ' ASC'); break;
            case 'price_desc': $query->orderByRaw($effectivePriceSql . ' DESC'); break;
            case 'newest': $query->orderBy('created_at', 'desc'); break;
            case 'rating': $query->orderBy('rating', 'desc'); break;
            default: $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }
        $products = $query->paginate(16)->withQueryString();
        return view('occasions.show', compact('occasion','products'));
    }
}
