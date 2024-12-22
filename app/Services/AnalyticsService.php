<?php

namespace App\Services;

use App\Repositories\AnalyticsRepository;

class AnalyticsService
{
    protected $analyticsRepository;
    public function __construct(AnalyticsRepository $analyticsRepository)
    {
        $this->analyticsRepository = $analyticsRepository;
    }
    public function getSalesData()
    {
        // Fetch sales data using Eloquent
        return $this->analyticsRepository->getSalesData();
    }

    public function getRevenueData()
    {
        // Fetch revenue data using Eloquent
        return $this->analyticsRepository->getRevenueData();
    }

    public function getCustomerGrowthData()
    {
        // Fetch customer growth data using Eloquent
        return $this->analyticsRepository->getCustomerGrowthData();
    }
}
