<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Inverse Relationships:
     * Product ↔ Category: One-to-Many.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
