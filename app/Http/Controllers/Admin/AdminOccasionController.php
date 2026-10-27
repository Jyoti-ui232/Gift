<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminOccasionController extends Controller
{
    public function index()
    {
        $occasions = Occasion::withCount('products')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.occasions.index', compact('occasions'));
    }

    public function create()
    {
        return view('admin.occasions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Occasion::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $data['slug'] = $slug;
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('occasions', 'public');
        }

        Occasion::create($data);

        return redirect()
            ->route('admin.occasions.index')
            ->with('success', 'Occasion created successfully!');
    }

    public function edit(Occasion $occasion)
    {
        return view('admin.occasions.edit', compact('occasion'));
    }

    public function update(Request $request, Occasion $occasion)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Occasion::where('slug', $slug)
                ->where('id', '!=', $occasion->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $data['slug'] = $slug;
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($occasion->image) {
                Storage::disk('public')->delete($occasion->image);
            }

            $data['image'] = $request->file('image')
                ->store('occasions', 'public');
        }

        $occasion->update($data);

        return redirect()
            ->route('admin.occasions.index')
            ->with('success', 'Occasion updated successfully!');
    }

    public function destroy(Occasion $occasion)
    {
        if ($occasion->image) {
            Storage::disk('public')->delete($occasion->image);
        }

        $occasion->delete();

        return redirect()
            ->route('admin.occasions.index')
            ->with('success', 'Occasion deleted successfully!');
    }
}
