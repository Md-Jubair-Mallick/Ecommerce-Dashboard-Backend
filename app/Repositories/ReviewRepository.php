<?php

namespace App\Repositories;

use App\Http\Helpers\ResponseHelper;
use App\Models\Review;

class ReviewRepository
{
    public function all($filters = [])
    {
        $query = Review::query();

        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (isset($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (isset($filters['rating_min']) && isset($filters['rating_max'])) {
            $query->whereBetween('rating', [$filters['rating_min'], $filters['rating_max']]);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween('created_at', [$filters['date_from'], $filters['date_to']]);
        }


        $per_page = $filters['per_page'] ?? 10;
        return $query->paginate($per_page);
    }
    public function update($id, $filters)
    {
        $review = Review::findOrFail($id);
        if (!$review) {
            return ResponseHelper::error('Review not found', 404);
        }
        $review->status = $filters['status'];
        $review->save();
        return $review;
    }
    public function delete($id)
    {
        $review = Review::destroy($id);
        if (!$review) {
            return ResponseHelper::error('Review not found', 404);
        }
        return $review;
    }
}
