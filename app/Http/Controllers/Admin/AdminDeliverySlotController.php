<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DeliverySlot;
use Illuminate\Http\Request;

class AdminDeliverySlotController extends Controller {
    public function index() { return view('admin.delivery-slots.index', ['slots' => DeliverySlot::orderBy('sort_order')->get()]); }
    public function create() { return view('admin.delivery-slots.create'); }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'time_range' => 'required|string|max:255',
            'type' => 'required|in:standard,express,midnight,fixed',
            'extra_charge' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data = $request->only(['name', 'time_range', 'type', 'extra_charge', 'sort_order']);
        $data['extra_charge'] = $request->input('extra_charge', 0) ?: 0;
        $data['sort_order'] = (int) ($request->input('sort_order', 0));
        $data['is_active'] = $request->has('is_active');
        DeliverySlot::create($data);
        return redirect()->route('admin.delivery-slots.index')->with('success', 'Delivery slot created!');
    }
    public function edit(DeliverySlot $deliverySlot) { return view('admin.delivery-slots.edit', compact('deliverySlot')); }
    public function update(Request $request, DeliverySlot $deliverySlot) {
        $request->validate([
            'name' => 'required|string|max:255',
            'time_range' => 'required|string|max:255',
            'type' => 'required|in:standard,express,midnight,fixed',
            'extra_charge' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data = $request->only(['name', 'time_range', 'type', 'extra_charge', 'sort_order']);
        $data['extra_charge'] = $request->input('extra_charge', 0) ?: 0;
        $data['sort_order'] = (int) ($request->input('sort_order', 0));
        $data['is_active'] = $request->has('is_active');
        $deliverySlot->update($data);
        return redirect()->route('admin.delivery-slots.index')->with('success', 'Delivery slot updated!');
    }
    public function destroy(DeliverySlot $deliverySlot) { $deliverySlot->delete(); return redirect()->route('admin.delivery-slots.index')->with('success', 'Delivery slot deleted!'); }
}
