<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller {
    public function index() {
        $categories = Category::with('parent')->withCount('products')->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }
    public function create() {
        $categories = Category::whereNull('parent_id')->where('is_active', true)->get();
        return view('admin.categories.create', compact('categories'));
    }
    public function store(Request $request) {
        $slug = Str::slug($request->input('name', ''));
        $request->merge(['slug' => $slug]);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('categories', 'slug')],
        ]);
        $data = $request->only([
            'name', 'parent_id', 'icon', 'description', 'meta_title', 'meta_description', 'sort_order',
        ]);
        $data['parent_id'] = $request->filled('parent_id') ? $request->parent_id : null;
        $data['slug'] = $slug;
        $data['is_active'] = $request->has('is_active');
        $data['show_in_nav'] = $request->has('show_in_nav');
        $data['sort_order'] = (int) ($request->input('sort_order', 0));
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }
    public function edit(Category $category) {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }
    public function update(Request $request, Category $category) {
        $slug = $request->filled('slug')
            ? Str::slug($request->input('slug'))
            : ($category->slug ?: Str::slug($request->input('name', '')));
        $request->merge(['slug' => $slug]);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                Rule::unique('categories', 'slug')->ignore($category->id),
            ],
        ]);
        $data = $request->only([
            'name', 'slug', 'parent_id', 'icon', 'description', 'meta_title', 'meta_description', 'sort_order',
        ]);
        $data['parent_id'] = $request->filled('parent_id') ? $request->parent_id : null;
        $data['slug'] = $slug;
        $data['is_active'] = $request->has('is_active');
        $data['show_in_nav'] = $request->has('show_in_nav');
        $data['sort_order'] = (int) ($request->input('sort_order', 0));
        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated!');
    }
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted!');
    }
}
