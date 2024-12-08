<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasUuids;
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
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
