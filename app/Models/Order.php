<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['customer_id', 'total_price', 'status'];

    /**
     * An order belongs to a customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    /**
     * An order can have many order items.
     */
    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
