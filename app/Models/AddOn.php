<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model {
    protected $fillable = ['name','image','price','type','is_active'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];
    public function getImageUrlAttribute() { return $this->image ? asset('storage/'.$this->image) : asset('images/placeholder.jpg'); }
}
