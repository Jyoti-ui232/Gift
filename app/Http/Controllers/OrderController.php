<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('confirmation');
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.confirmation', compact('order'));
    }

    public function trackForm()
    {
        return view('orders.track');
    }

    public function track(Request $request)
    {
        $data = $request->validate([
            'order_number' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
        ]);

        $orderNumber = strtoupper(trim($data['order_number']));
        $phoneDigits = $this->normalizePhone($data['phone']);

        $order = Order::query()
            ->whereRaw('UPPER(order_number) = ?', [$orderNumber])
            ->first();

        $orderPhoneDigits = $order
            ? $this->normalizePhone((string) $order->recipient_phone)
            : null;

        if (!$order || !$phoneDigits || $orderPhoneDigits !== $phoneDigits) {
            return back()
                ->withInput()
                ->withErrors([
                    'track' => 'We could not find an order matching those details. Please check and try again.',
                ]);
        }

        return view('orders.track', [
            'order' => $order,
            'found' => true,
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (strlen($digits) > 10) {
            return substr($digits, -10);
        }

        return $digits;
    }
}
