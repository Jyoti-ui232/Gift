<?php
namespace App\Http\Controllers;
use App\Models\{Product, Category};
use Illuminate\Http\Request;

class SearchController extends Controller {
    public function index(Request $request) {
        $q = $request->get('q', '');
        $products = collect();
        $categories = collect();
        if (strlen($q) >= 2) {
            $products = Product::active()->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%");
            })->with(['images', 'variants'])->paginate(16);
            $categories = Category::where('is_active', true)->where('name', 'like', "%{$q}%")->get();
        }
        return view('search.index', compact('products','categories','q'));
    }
}
