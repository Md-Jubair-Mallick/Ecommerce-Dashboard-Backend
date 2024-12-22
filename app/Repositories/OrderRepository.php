<?php

namespace App\Repositories;

use App\Http\Helpers\ResponseHelper;
use App\Models\Order;

class OrderRepository
{
    public function all($filters = [])
    {
        $query = Order::query();

        // Filters by status.
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range (created_at)
        if (isset($filters['date_start']) || isset($filters['date_end'])) {
            $date_start = $filters['date_start'] ?? now()->subYear()->startOfDay();
            $date_end = $filters['date_end'] ?? now()->endOfDay();

            $query->whereBetween('created_at', [
                $date_start,
                $date_end,
            ]);
        }

        // Sort by date
        if (isset($filters['sort_order'])) {
            $sort_order = $filters['sort_order'] ?? 'desc';
            $query->orderBy('created_at', $sort_order);
        }

        // Handle pagination
        $perPage = $filters['per_page'] ?? 10;
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        return $query->paginate($perPage);
    }
    public function find($id)
    {
        $order = Order::with('customer', 'orderItems.product')
            ->find($id);

        if (!$order) {
            return ResponseHelper::error('Order not found', 404);
        }
        return $order;
    }
    public function update($id, $filters)
    {
        $order = Order::findOrFail($id);
        if (!$order) {
            return ResponseHelper::error('Order not found', 404);
        }
        $order->status = $filters['status'];
        $order->save();
        return $order;
    }
}
