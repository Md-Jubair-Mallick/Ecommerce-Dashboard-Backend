<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Relationships:
     * Product ↔ Category: One-to-Many.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
