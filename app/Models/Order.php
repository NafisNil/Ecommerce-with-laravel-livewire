<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['customer_id','order_number','coupon_id','order_date', 'status', 'total_amount', 'payment_method', 'shipping_address_id', 'billing_address_id', 'subtotal', 'discount', 'shipping_cost', 'tax', 'payment_status', 'transaction_id', 'tracking_number', 'customer_notes', 'admin_notes'])]
class Order extends Model
{
    //
    use SoftDeletes;
    #[Scope()]
    protected function ofStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }
    #[Scope()]
    protected function paymentStatus(Builder $query, $paymentStatus): Builder
    {
        return $query->where('payment_status', $paymentStatus);
    }
    #[Scope()]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    #[Scope()]
    protected function processing(Builder $query): Builder
    {
        return $query->where('status', 'processing');
    }

    #[Scope()]
    protected function completed(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    #[Scope()]
    protected function cancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }


    public function getShippingAddressAttribute()
    {
        return implode(', ', [
            $this->shipping_address_line1,
            $this->shipping_address_line2,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_postal_code,
            $this->shipping_country
        ]);
    }


    public function updateStatus($newStatus, $notes = null, $userId = null)
    {
        $this->update([
            'status' => $newStatus,
            'admin_notes' => $notes,
        ]);

        $this->statusHistories()->create([
            'status' => $newStatus,
            'changed_by' => $userId,
            'notes' => $notes,
        ]);
    }

    //relations
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(){
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });

        static::created(function ($order) {
            $order->statusHistories()->create([
                'status' => $order->status,
                'changed_by' => null,
                'notes' => 'Order created with status: ' . $order->status,
            ]);
        });


    }
}
