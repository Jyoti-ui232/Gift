<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','parent_id','image','icon','description','meta_title','meta_description','sort_order','is_active','show_in_nav'];
    protected $casts = ['is_active' => 'boolean', 'show_in_nav' => 'boolean'];
    
    public function parent() { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function products() { return $this->hasMany(Product::class); }
    public function activeProducts() { return $this->hasMany(Product::class)->where('is_active', true); }
    public function additionalProducts() { return $this->belongsToMany(Product::class, 'category_product')->withTimestamps(); }
    
    public function getRouteKeyName() { return 'slug'; }
    
    public static function getNavCategories() {
        return static::where('is_active', true)->where('show_in_nav', true)->whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
    }
}
