<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Occasion, ProductImage};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller {
    public function index(Request $request) {
        $query = Product::with('category');
        if ($request->search) $query->where('name', 'like', '%'.$request->search.'%');
        if ($request->category_id) {
            $query->where(function ($productsQuery) use ($request) {
                $productsQuery->where('category_id', $request->category_id)
                    ->orWhereHas('additionalCategories', fn ($categoriesQuery) => $categoriesQuery->whereKey($request->category_id));
            });
        }
        if ($request->status === 'active') $query->where('is_active', true);
        if ($request->status === 'inactive') $query->where('is_active', false);
        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.index', compact('products','categories'));
    }
    
    public function create() {
        $categories = Category::where('is_active', true)->get();
        $additionalCategories = $categories;
        $occasions = Occasion::where('is_active', true)->get();
        return view('admin.products.create', compact('categories','additionalCategories','occasions'));
    }

    public function show(Product $product) {
        return redirect()->route('admin.products.edit', $product);
    }
    
    public function store(Request $request) {
        $validated = $this->validateProductForm($request);
        $data = $this->productAttributes($validated);
        $data['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        $product = Product::create($data);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create(['product_id' => $product->id, 'image' => $path, 'is_primary' => $i === 0, 'sort_order' => $i]);
            }
        }
        $this->syncProductOccasions($request, $validated, $product);
        $this->syncProductAdditionalCategories($request, $validated, $product);
        $this->saveProductVariants($validated, $product);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }
    
    public function edit(Product $product) {
        $product->load(['images', 'variants', 'occasions', 'additionalCategories']);
        $categories = Category::where('is_active', true)->get();
        $additionalCategories = Category::where('is_active', true)
            ->orWhereIn('id', $product->additionalCategories->modelKeys())
            ->orderBy('name')
            ->get();
        $occasions = Occasion::all();
        return view('admin.products.edit', compact('product','categories','additionalCategories','occasions'));
    }
    
    public function update(Request $request, Product $product) {
        $validated = $this->validateProductForm($request, $product);
        $data = $this->productAttributes($validated);
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) Storage::disk('public')->delete($product->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        $product->update($data);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create(['product_id' => $product->id, 'image' => $path, 'is_primary' => false, 'sort_order' => $product->images()->count() + $i]);
            }
        }
        $this->syncProductOccasions($request, $validated, $product);
        $this->syncProductAdditionalCategories($request, $validated, $product);
        $this->saveProductVariants($validated, $product);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    private function validateProductForm(Request $request, ?Product $product = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'sale_price' => ['nullable', 'numeric', 'decimal:0,2', 'gt:0', 'max:99999999.99', 'lte:price'],
            'stock' => [
                $product ? 'nullable' : 'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'sku' => [
                'required',
                'string',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'short_description' => 'nullable|string|max:65535',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'occasions_present' => 'nullable|boolean',
            'occasions' => 'nullable|array',
            'occasions.*' => 'integer|exists:occasions,id',
            'additional_categories_present' => 'nullable|boolean',
            'additional_categories' => 'nullable|array',
            'additional_categories.*' => [
                'integer',
                'distinct',
                'exists:categories,id',
                Rule::notIn(array_filter([$request->input('category_id')])),
            ],
            'variants' => 'sometimes|array',
            'variants.*' => 'array:id,name,type,price,sale_price,stock,sku,is_default',
            'variants.*.id' => $product
                ? ['nullable', 'integer', Rule::exists('product_variants', 'id')->where('product_id', $product->id)]
                : 'prohibited',
            'variants.*.name' => 'nullable|string|max:255',
            'variants.*.type' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|decimal:0,2|min:0|max:99999999.99',
            'variants.*.sale_price' => 'nullable|numeric|decimal:0,2|gt:0|max:99999999.99',
            'variants.*.stock' => 'nullable|integer|min:0|max:2147483647',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.is_default' => 'nullable|boolean',
        ];

        foreach ($request->input('variants', []) as $index => $variant) {
            if (filled($variant['name'] ?? null) || filled($variant['id'] ?? null)) {
                $rules["variants.$index.name"] = 'required|string|max:255';
                $rules["variants.$index.type"] = 'required|string|max:255';
                $rules["variants.$index.price"] = 'required|numeric|decimal:0,2|min:0|max:99999999.99';
                $rules["variants.$index.sale_price"] = 'nullable|numeric|decimal:0,2|gt:0|max:99999999.99|lte:variants.' . $index . '.price';
                $rules["variants.$index.stock"] = 'required|integer|min:0|max:2147483647';
            }
        }

        return $request->validate($rules);
    }

    private function productAttributes(array $validated): array
    {
        $attributes = array_intersect_key($validated, array_flip([
            'name',
            'category_id',
            'price',
            'sale_price',
            'stock',
            'sku',
            'short_description',
            'description',
        ]));

        $attributes['is_active'] = (bool) ($validated['is_active'] ?? false);
        $attributes['is_featured'] = (bool) ($validated['is_featured'] ?? false);
        $attributes['is_bestseller'] = (bool) ($validated['is_bestseller'] ?? false);
        $attributes['is_new_arrival'] = (bool) ($validated['is_new_arrival'] ?? false);

        return $attributes;
    }

    private function syncProductOccasions(Request $request, array $validated, Product $product): void
    {
        if ($request->exists('occasions_present') || $request->exists('occasions')) {
            $product->occasions()->sync($validated['occasions'] ?? []);
        }
    }

    private function syncProductAdditionalCategories(Request $request, array $validated, Product $product): void
    {
        if ($request->exists('additional_categories_present') || $request->exists('additional_categories')) {
            $product->additionalCategories()->sync($validated['additional_categories'] ?? []);
        }
    }

    private function saveProductVariants(array $validated, Product $product): void
    {
        if (!array_key_exists('variants', $validated)) {
            return;
        }

        foreach ($validated['variants'] as $variantData) {
            if (blank($variantData['name'] ?? null)) {
                continue;
            }

            $variantId = $variantData['id'] ?? null;
            unset($variantData['id']);
            $variantData['is_default'] = (bool) ($variantData['is_default'] ?? false);

            if ($variantId) {
                $product->variants()->whereKey($variantId)->firstOrFail()->update($variantData);
            } else {
                $product->variants()->create($variantData);
            }
        }
    }
    
    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted!');
    }
    
    public function toggleActive(Product $product) {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }
}
