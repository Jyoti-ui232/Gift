<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model {
    protected $fillable = ['name','time_range','type','extra_charge','is_active','sort_order'];
    protected $casts = ['extra_charge' => 'decimal:2', 'is_active' => 'boolean'];
}
