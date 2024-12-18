<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{

    public function __construct(private readonly AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getSales()
    {
        $data = $this->analyticsService->getSalesData();
        return response()->json(['total_sales' => $data]);
    }
    public function getRevenue()
    {
        $data = $this->analyticsService->getRevenueData();
        return response()->json(['total_revenue' => $data]);
    }
    public function getCustomers()
    {
        $data = $this->analyticsService->getCustomerGrowthData();
        return response()->json(['total_customers' => $data]);
    }
    /**
     * Get daily revenue for the past N days.
     */
    public function getDailyRevenue($days = 30)
    {
        $dailyRevenue = $this->analyticsService->getDailyRevenue($days);
        return response()->json(['daily_revenue' => $dailyRevenue]);
    }

    /**
     * Get orders per product category.
     */
    public function getOrdersPerCategory()
    {
        $ordersPerCategory = $this->analyticsService->getOrdersPerCategory();
        return response()->json(['orders_per_category' => $ordersPerCategory]);
    }
}
