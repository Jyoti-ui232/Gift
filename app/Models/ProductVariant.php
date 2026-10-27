<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model {
    protected $fillable = ['product_id','name','type','price','sale_price','stock','sku','is_default'];
    protected $casts = ['price'=>'decimal:2','sale_price'=>'decimal:2','is_default'=>'boolean'];
    public function product() { return $this->belongsTo(Product::class); }
    public function getCurrentPriceAttribute() {
        $salePrice = (float) $this->sale_price;

        return $this->sale_price !== null && $salePrice > 0 && $salePrice < (float) $this->price
            ? $this->sale_price
            : $this->price;
    }
}
