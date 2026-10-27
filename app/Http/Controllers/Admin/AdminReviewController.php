<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller {
    public function index() { return view('admin.reviews.index', ['reviews' => Review::with(['product','user'])->orderBy('created_at','desc')->paginate(20)]); }
    public function approve(Review $review) { $review->update(['is_approved' => !$review->is_approved]); return redirect()->route('admin.reviews.index')->with('success', 'Review status updated!'); }
    public function destroy(Review $review) { $review->delete(); return redirect()->route('admin.reviews.index')->with('success', 'Review deleted!'); }
}
