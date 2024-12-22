<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
            $filters = $req->only([
                'status',
                'date_start',
                'date_end',
                'sort_order',
                'per_page'
            ]);
            $orders = $this->orderService->all($filters);

            return ResponseHelper::success($orders, 'Orders retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve orders', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = $this->orderService->find($id);
            return ResponseHelper::success($order, 'Order retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to retrieve order', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        try {
            $conditions = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
            if (isset($req->status) && !in_array($req->status, $conditions)) {
                return ResponseHelper::error('Invalid status', 400);
            }
            $filters = $req->only(['status']);
            $filters['status'] = $req->status;
            $order = $this->orderService->update($id, $filters);
            return ResponseHelper::success($order, 'Order updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update order', 500);
        }
    }
}
