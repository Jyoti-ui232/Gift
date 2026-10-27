<?php
namespace App\Http\Controllers;
use App\Models\{Review, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|integer|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
        ]);

        $created = DB::transaction(function () use ($request, $validated) {
            $user = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $order = $user->orders()
                ->whereKey($validated['order_id'])
                ->whereHas('items', fn ($query) => $query->where('product_id', $validated['product_id']))
                ->first();

            abort_unless($order, 403, 'The order does not belong to you or does not contain this product.');

            $existing = Review::where('user_id', $user->id)
                ->where('product_id', $validated['product_id'])
                ->exists();

            if ($existing) {
                return false;
            }

            $review = new Review([
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'comment' => $validated['comment'] ?? null,
            ]);
            $review->product()->associate($validated['product_id']);
            $review->user()->associate($user);
            $order->reviews()->save($review);
            return true;
        });

        if (!$created) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        return redirect()->back()->with('success', 'Review submitted! It will appear after approval.');
    }
}
