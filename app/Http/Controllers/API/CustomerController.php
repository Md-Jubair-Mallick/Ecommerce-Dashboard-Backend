<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Services\CustomerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
            $filters = $req->only(['status', 'per_page']);
            $customers = $this->customerService->index($filters);
            return ResponseHelper::success($customers, 'Customers retrieved successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        try {
            $validated = $req->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
            ]);
            $customer = $this->customerService->store($validated);
            return ResponseHelper::success($customer, 'Customer created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = $this->customerService->show($id);

            if (!$customer) {
                return ResponseHelper::error('Customer not found', 404);
            }
            return ResponseHelper::success($customer, 'Customer retrieved successfully', 200);
        } catch (\Throwable $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    public function update(Request $req, string $id)
    {
        try {
            $validated = $req->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:customers,email,' . $id,
                'phone' => 'nullable|string|max:13',
                'address' => 'nullable|string',
                'status' => 'sometimes|required|in:block,unblock',
            ]);

            $customer = $this->customerService->update($id, $validated);

            if (!$customer) {
                return ResponseHelper::error('Customer not found', 404);
            }

            return ResponseHelper::success($customer, 'Customer updated successfully', 200);
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
            $customer = $this->customerService->destroy($id);
            return ResponseHelper::success([], 'Customer deleted successfully', 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error('Customer not found', 404);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}