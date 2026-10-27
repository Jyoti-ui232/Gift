<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderInventoryRestorer
{
    public function restore(Order $lockedOrder): void
    {
        if (
            !$lockedOrder->inventory_restoration_eligible
            || $lockedOrder->inventory_restored_at !== null
        ) {
            return;
        }

        $items = $lockedOrder->items()->lockForUpdate()->get();
        $productQuantities = $items
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->map(fn ($productItems) => $productItems->sum('quantity'));

        $products = Product::whereIn('id', $productQuantities->keys())
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($productQuantities as $productId => $quantity) {
            if ($products->has($productId)) {
                $products->get($productId)->increment('stock', $quantity);
            }
        }

        $variantQuantities = $items
            ->whereNotNull('product_variant_id')
            ->groupBy('product_variant_id')
            ->map(fn ($variantItems) => $variantItems->sum('quantity'));

        $variants = ProductVariant::whereIn('id', $variantQuantities->keys())
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($variantQuantities as $variantId => $quantity) {
            if ($variants->has($variantId)) {
                $variants->get($variantId)->increment('stock', $quantity);
            }
        }

        $lockedOrder->inventory_restored_at = now();
    }
}
