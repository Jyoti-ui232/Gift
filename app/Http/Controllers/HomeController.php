<?php
namespace App\Http\Controllers;
use App\Models\{Banner, Category, Product, Occasion, Setting};
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index() {
        $heroBanners = Banner::where('position', 'hero')->where('is_active', true)->orderBy('sort_order')->get();
        $promoBanners = Banner::where('position', 'promo')->where('is_active', true)->orderBy('sort_order')->limit(4)->get();
        $navCategories = Category::where('is_active', true)->where('show_in_nav', true)->whereNull('parent_id')->with(['children' => function($q){ $q->where('is_active', true); }])->orderBy('sort_order')->get();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->limit(12)->get();
        $featuredProducts = Product::active()->featured()->with(['images', 'variants'])->orderBy('sort_order')->limit(8)->get();
        $bestsellerProducts = Product::active()->bestseller()->with(['images', 'variants'])->orderBy('sort_order')->limit(8)->get();
        $newArrivals = Product::active()->newArrival()->with(['images', 'variants'])->orderBy('created_at', 'desc')->limit(8)->get();
        $occasions = Occasion::where('is_active', true)->orderBy('sort_order')->limit(12)->get();
        $trendingProducts = Product::active()->with(['images', 'variants'])->inRandomOrder()->limit(12)->get();
        return view('home', compact('heroBanners','promoBanners','navCategories','categories','featuredProducts','bestsellerProducts','newArrivals','occasions','trendingProducts'));
    }
}
