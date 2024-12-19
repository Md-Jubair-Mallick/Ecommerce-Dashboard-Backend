<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class AnalyticsService
{
    public function getSalesData()
    {
        // Fetch sales data using Eloquent
        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_sales')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getRevenueData()
    {
        // Fetch revenue data using Eloquent
        return Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getCustomerGrowthData()
    {
        // Fetch customer growth data using Eloquent
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as new_customers')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
