<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
            $query = Review::query();

            if ($req->has('product_id')) {
                $query->where('product_id', $req->product_id);
            }

            if ($req->has('rating')) {
                $query->where('rating', $req->rating);
            }

            if ($req->has('rating_min') && $req->has('rating_max')) {
                $query->whereBetween('rating', [$req->rating_min, $req->rating_max]);
            }

            if ($req->has('status')) {
                $query->where('status', $req->status);
            }

            if ($req->has('customer_id')) {
                $query->where('customer_id', $req->customer_id);
            }

            if ($req->has('date_from') && $req->has('date_to')) {
                $query->whereBetween('created_at', [$req->date_from, $req->date_to]);
            }


            $per_page = $req->input('per_page', 10);
            $reviews = $query->paginate($per_page);

            return response()->json([
                'success' => true,
                'result' => [
                    'data' => $reviews->items(),
                    'pagination' => [
                        'total' => $reviews->total(),
                        'per_page' => $reviews->perPage(),
                        'current_page' => $reviews->currentPage(),
                        'last_page' => $reviews->lastPage(),
                        'from' => $reviews->firstItem(),
                        'to' => $reviews->lastItem(),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        try {
            $reviews = Review::find($id);
            $reviews->status = $req->status;
            $reviews->save();
            return response()->json([
                'success' => true,
                'result' => $reviews,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Review::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

/**
 * TODOS
 * - Implement the logic for the store method
 * -- - **Features:**
 */
/**
 * DONE
 * - Fetch reviews with filtering options (e.g., by product, rating, etc).
 * - Approve, reject, or delete reviews.
 * - 
 */
