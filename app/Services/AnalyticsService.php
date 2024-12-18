<?php
namespace App\Services;

// use App\Models\Order;
// use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    /**
     * Get total sales count.
     */
    public function getSalesData()
    {
        return Cache::remember('sales_data', now()->addMinutes(10), function () {
            return DB::table('orders')
                ->where('status', 'completed')
                ->count();
        });
    }

    /**
     * Get total revenue.
     */
    public function getRevenueData()
    {
        return Cache::remember('revenue_data', now()->addMinutes(10), function () {
            return DB::table('orders')
                ->where('status', 'completed')
                ->sum('total_price');
        });
    }

    /**
     * Get customer growth data.
     */
    public function getCustomerGrowthData($days = 30)
    {
        return Cache::remember("customer_growth_data_{$days}_days", now()->addMinutes(10), function () use ($days) {
            return DB::table('users')
                ->where('created_at', '>=', now()->subDays($days))
                ->count();
        });
    }

    /**
     * Get daily revenue for the past N days.
     */
    public function getDailyRevenue($days = 30)
    {
        return Cache::remember("daily_revenue_{$days}_days", now()->addMinutes(10), function () use ($days) {
            return DB::table('orders')
                ->select(DB::raw('DATE(created_at) as order_date'), DB::raw('SUM(total_price) as daily_revenue'))
                ->where('status', 'completed')
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'), 'asc')
                ->get();
        });
    }

    /**
     * Get orders per product category.
     */
    public function getOrdersPerCategory()
    {
        return Cache::remember('orders_per_category', now()->addMinutes(10), function () {
            return DB::table('orders as o')
                ->join('products as p', 'o.product_id', '=', 'p.id')
                ->select('p.category', DB::raw('COUNT(o.id) as total_orders'))
                ->where('o.status', 'completed')
                ->groupBy('p.category')
                ->orderBy('total_orders', 'desc')
                ->get();
        });
    }
}
