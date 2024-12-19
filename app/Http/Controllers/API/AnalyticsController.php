<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getSalesData(): JsonResponse
    {
        $salesData = $this->analyticsService->getSalesData();
        return response()->json(['data' => $salesData], 200);
    }

    public function getRevenueData(): JsonResponse
    {
        $revenueData = $this->analyticsService->getRevenueData();
        return response()->json(['data' => $revenueData], 200);
    }

    public function getCustomerGrowthData(): JsonResponse
    {
        $customerData = $this->analyticsService->getCustomerGrowthData();
        return response()->json(['data' => $customerData], 200);
    }
}
