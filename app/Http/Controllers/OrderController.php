<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
            $query = Order::query();

            // Filters by status.
            if ($req->has('status')) {
                $query->where('status', $req->status);
            }

            // Filter by date range (created_at)
            if ($req->has('date_start') || $req->has('date_end')) {
                // Parse date_start and date_end using Carbon for flexibility
                $date_start = $req->input('date_start', Carbon::now()->subYear()->startOfDay());
                $date_end = $req->input('date_end', Carbon::now()->endOfDay());

                // Ensure the dates are in the correct format (e.g., 'Y-m-d H:i:s')
                $query->whereBetween('created_at', [
                    Carbon::parse($date_start)->startOfDay(),
                    Carbon::parse($date_end)->endOfDay(),
                ]);
            }

            // Sort by date
            if ($req->has('sort_order')) {
                $sort_order = $req->input('sort_order', 'desc');
                $query->orderBy('created_at', $sort_order);
            }

            // Handle pagination
            $perPage = $req->input('per_page', 10);
            if ($perPage < 1 || $perPage > 100) {
                // Set a reasonable pagination limit
                $perPage = 10;
            }

            // Paginate the results
            $orders = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'result' => $orders,
                'meta' => [
                    'total' => $orders->total(),
                    'per_page' => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // $order = Order::with(['customer', 'OrderItems' => function ($query) use ($id) {
                // $query->where('order_id', $id);
            // }])->find($id);
            $order = Order::with('customer',  'orderItems')
            ->get();
            // ->find($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'result' => $order,
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

/**
 * TODOS
 * - Implement the logic for the OrderController
 * - **Features:**
 * -- View order details, including customer and product information.
 * -- Update order status (e.g., `Pending`, `Shipped`, `Delivered`, `Cancelled`)
 * 
 */
/**
 * DONE
 * -- List orders with filters (e.g., status, date) and sort (e.g., date) and pagination.
 * 
 */
