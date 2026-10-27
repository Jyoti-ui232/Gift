<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\User;

class Coupon extends Model {
    protected $fillable = ['code','type','value','min_order_amount','max_discount','usage_limit','used_count','per_user_limit','starts_at','expires_at','is_active','description'];
    protected $casts = ['starts_at' => 'datetime', 'expires_at' => 'datetime', 'is_active' => 'boolean'];
    
    public function isValid(?User $user = null): bool {
        if (!$this->is_active) return false;
        if ($this->starts_at && Carbon::now()->lt($this->starts_at)) return false;
        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->hasReachedUserLimit($user)) return false;
        return true;
    }

    public function hasReachedUserLimit(?User $user): bool {
        if (!$user || $this->per_user_limit === null) return false;

        return Order::where('user_id', $user->id)
            ->where('coupon_code', $this->code)
            ->count() >= $this->per_user_limit;
    }
    
    public function calculateDiscount($subtotal) {
        if ($subtotal < $this->min_order_amount) return 0;
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount) $discount = min($discount, $this->max_discount);
            return $discount;
        }
        return min($this->value, $subtotal);
    }
}
