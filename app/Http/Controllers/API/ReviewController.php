<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $reviewService;
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try { 
            $filters = $req->all();
            $reviews = $this->reviewService->all($filters);
            return ResponseHelper::success($reviews, 'Reviews retrieved successfully', 200);
            } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        try {
            $conditions = ['pending', 'approved', 'rejected'];
            if (isset($req->status) && !in_array($req->status, $conditions)) {
                return ResponseHelper::error('Invalid status', 400);
            }
            $filters = $req->only(['status']);
            $filters['status'] = $req->status;
            $reviews = $this->reviewService->update($id, $filters);
            return ResponseHelper::success($reviews, 'Review updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->reviewService->delete($id);
            return ResponseHelper::success([], 'Review deleted successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}
