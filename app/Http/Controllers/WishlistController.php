<?php
namespace App\Http\Controllers;
use App\Models\{Wishlist, Product};
use Illuminate\Http\Request;

class WishlistController extends Controller {
    public function toggle(Request $request, Product $product) {
        $wishlist = Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->first();
        if ($wishlist) {
            $wishlist->delete();
            $action = 'removed';
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $product->id]);
            $action = 'added';
        }
        if ($request->ajax()) {
            return response()->json(['success' => true, 'action' => $action]);
        }
        return redirect()->back()->with('success', 'Wishlist updated!');
    }
}
