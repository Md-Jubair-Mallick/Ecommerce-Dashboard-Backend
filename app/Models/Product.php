<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids, HasFactory;
    /**
     * Inverse Relationships:
     * Product ↔ Category: One-to-Many.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    protected $fillable = ['name', 'price', 'description', 'category_id' ,'stock'];
}
