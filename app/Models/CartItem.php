<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model {
    protected $fillable = ['session_id','user_id','product_id','variant_id','quantity','add_ons','gift_message'];
    protected $casts = ['add_ons' => 'array'];
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class); }
    public function getUnitPriceAttribute() {
        return $this->variant
            ? $this->variant->current_price
            : $this->product->current_price;
    }
    public function getTotalAttribute() {
        $addOnsTotal = collect($this->add_ons ?? [])->sum('price');
        return ($this->unit_price + $addOnsTotal) * $this->quantity;
    }
}
