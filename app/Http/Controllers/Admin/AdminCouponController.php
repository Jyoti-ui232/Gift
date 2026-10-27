<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller {
    public function index() { return view('admin.coupons.index', ['coupons' => Coupon::orderBy('created_at', 'desc')->paginate(20)]); }
    public function create() { return view('admin.coupons.create'); }
    public function store(Request $request) {
        $data = $this->validatedCouponData($request);
        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created!');
    }
    public function edit(Coupon $coupon) { return view('admin.coupons.edit', compact('coupon')); }
    public function update(Request $request, Coupon $coupon) {
        $data = $this->validatedCouponData($request, $coupon);
        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated!');
    }

    private function validatedCouponData(Request $request, ?Coupon $coupon = null): array
    {
        $uniqueCode = 'unique:coupons,code';
        if ($coupon) {
            $uniqueCode .= ',' . $coupon->id;
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', $uniqueCode],
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'description' => 'nullable|string|max:1000',
        ]);

        return [
            'code' => strtoupper(trim($validated['code'])),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_order_amount' => $validated['min_order_amount'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'per_user_limit' => $validated['per_user_limit'] ?? 1,
            'starts_at' => $validated['starts_at'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ];
    }
    public function destroy(Coupon $coupon) { $coupon->delete(); return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted!'); }
}
