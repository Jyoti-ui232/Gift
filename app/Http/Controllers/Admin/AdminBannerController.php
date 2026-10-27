<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller {
    public function index() { return view('admin.banners.index', ['banners' => Banner::orderBy('sort_order')->paginate(20)]); }
    public function create() { return view('admin.banners.create'); }
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'mobile_image' => 'nullable|image|max:2048',
            'link' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'position' => 'required|in:hero,promo,category',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['title', 'subtitle', 'link', 'button_text', 'position', 'sort_order']);
        $data['image'] = $request->file('image')->store('banners', 'public');
        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }
        $data['sort_order'] = (int) ($request->input('sort_order', 0));
        $data['is_active'] = $request->has('is_active');

        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner created!');
    }
    public function edit(Banner $banner) { return view('admin.banners.edit', compact('banner')); }
    public function update(Request $request, Banner $banner) {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'mobile_image' => 'nullable|image|max:2048',
            'link' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'position' => 'required|in:hero,promo,category',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['title', 'subtitle', 'link', 'button_text', 'position', 'sort_order']);
        $data['sort_order'] = (int) ($request->input('sort_order', 0));
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated!');
    }
    public function destroy(Banner $banner) {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        if ($banner->mobile_image) {
            Storage::disk('public')->delete($banner->mobile_image);
        }
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted!');
    }
}
