<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Occasion extends Model {
    protected $fillable = ['name','slug','image','description','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];
    public function products() { return $this->belongsToMany(Product::class); }
    public function getRouteKeyName() { return 'slug'; }
}
