<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['order_number','user_id','status','subtotal','discount','delivery_charge','total','coupon_code','payment_method','payment_status','payment_id','recipient_name','recipient_phone','recipient_address','recipient_city','recipient_state','recipient_pincode','delivery_type','delivery_date','delivery_slot','gift_message','special_instructions','tracking_number','courier_name','tracking_url','inventory_restoration_eligible'];
    protected $casts = [
        'delivery_date' => 'date',
        'inventory_restored_at' => 'datetime',
        'inventory_restoration_eligible' => 'boolean',
    ];
    
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    
    public static function generateOrderNumber() {
        return 'GB' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    public function getStatusBadgeAttribute() {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
