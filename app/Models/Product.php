<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','category_id','short_description','description','price','sale_price','sku','stock','thumbnail','weight','dimensions','delivery_type','is_active','is_featured','is_bestseller','is_new_arrival','rating','reviews_count','meta_title','meta_description','sort_order'];
    protected $casts = ['is_active'=>'boolean','is_featured'=>'boolean','is_bestseller'=>'boolean','is_new_arrival'=>'boolean','price'=>'decimal:2','sale_price'=>'decimal:2'];
    
    public function category() { return $this->belongsTo(Category::class); }
    public function additionalCategories() { return $this->belongsToMany(Category::class, 'category_product')->withTimestamps(); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function primaryImage() { return $this->hasOne(ProductImage::class)->where('is_primary', true); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function defaultVariant() { return $this->hasOne(ProductVariant::class)->where('is_default', true); }
    public function occasions() { return $this->belongsToMany(Occasion::class); }
    public function reviews() { return $this->hasMany(Review::class)->where('is_approved', true); }
    public function wishlists() { return $this->hasMany(Wishlist::class); }
    
    public function getRouteKeyName() { return 'slug'; }
    
    public function getCurrentPriceAttribute() {
        $salePrice = (float) $this->sale_price;

        return $this->sale_price !== null && $salePrice > 0 && $salePrice < (float) $this->price
            ? $this->sale_price
            : $this->price;
    }

    public static function effectivePriceSql(): string {
        return '(CASE WHEN sale_price IS NOT NULL AND sale_price > 0 AND sale_price < price THEN sale_price ELSE price END)';
    }
    
    public function getDiscountPercentAttribute() {
        $price = (float) $this->price;
        $currentPrice = (float) $this->current_price;

        if ($price > 0 && $currentPrice < $price) {
            return round((($price - $currentPrice) / $price) * 100);
        }
        return 0;
    }
    
    public function getThumbnailUrlAttribute() {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('assets/image/roses.jpg');
    }
    
    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeBestseller($query) { return $query->where('is_bestseller', true); }
    public function scopeNewArrival($query) { return $query->where('is_new_arrival', true); }
}
