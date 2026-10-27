<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderInventoryRestorer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminOrderController extends Controller {
    public function index(Request $request) {
        $query = Order::with('user');
        if ($request->status) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', '%'.$term.'%')
                    ->orWhere('recipient_name', 'like', '%'.$term.'%')
                    ->orWhere('recipient_phone', 'like', '%'.$term.'%')
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', '%'.$term.'%')
                            ->orWhere('email', 'like', '%'.$term.'%');
                    });
            });
        }
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);
        $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }
    public function show(Order $order) {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order) {
        return redirect()->route('admin.orders.show', $order);
    }

    public function update(Request $request, Order $order) {
        $trackingUrl = trim((string) $request->input('tracking_url', ''));
        if ($trackingUrl !== '' && !preg_match('#^https?://#i', $trackingUrl)) {
            $trackingUrl = 'https://' . $trackingUrl;
        }

        $request->merge([
            'tracking_number' => $request->filled('tracking_number') ? trim($request->tracking_number) : null,
            'courier_name' => $request->filled('courier_name') ? trim($request->courier_name) : null,
            'tracking_url' => $trackingUrl !== '' ? $trackingUrl : null,
        ]);

        $data = $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'courier_name' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:500',
        ]);

        $order->forceFill([
            'tracking_number' => $data['tracking_number'] ?? null,
            'courier_name' => $data['courier_name'] ?? null,
            'tracking_url' => $data['tracking_url'] ?? null,
        ])->save();

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Shipment tracking updated!');
    }

    public function updateStatus(Request $request, Order $order) {
        $validated = $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);

        if ($validated['status'] === 'cancelled') {
            $schemaReady = Schema::hasColumn('orders', 'inventory_restored_at')
                && Schema::hasColumn('orders', 'inventory_restoration_eligible')
                && Schema::hasColumn('order_items', 'product_variant_id');

            if (!$schemaReady) {
                $message = 'Order cancellation is unavailable until the inventory restoration migration has been applied.';

                return $request->expectsJson()
                    ? response()->json(['message' => $message], 503)
                    : response($message, 503);
            }
        }

        DB::transaction(function () use ($order, $validated) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($validated['status'] === 'cancelled') {
                abort_unless(
                    in_array($lockedOrder->status, ['pending', 'confirmed', 'processing'], true),
                    409,
                    'Only pending, confirmed, or processing orders can be cancelled.'
                );

                app(OrderInventoryRestorer::class)->restore($lockedOrder);
            }

            $lockedOrder->status = $validated['status'];
            $lockedOrder->save();
        });

        if ($request->ajax()) return response()->json(['success' => true]);
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated!');
    }
}
