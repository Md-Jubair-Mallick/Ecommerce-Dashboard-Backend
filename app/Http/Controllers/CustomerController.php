<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
            $query = Customer::query();

            // Filters by status.
            if ($req->has('status')) {
                $query->where('status', $req->input('status'));
            }

            $per_page = $req->input('per_page', 10);
            $customer = $query->paginate($per_page);

            return response()->json([
                'success' => true,
                'result' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
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
            $customer = Customer::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => $customer,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = Customer::with('orders')->find($id);

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer Not Found']);
            }
            return response()->json([
                'success' => true,
                'result' => $customer
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => true, 'error' => $e->getMessage()]);
        }
    }

    public function update(Request $req, string $id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer Not Found'], 404);
            }

            $validated = $req->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:customers,email,' . $id,
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
                'status' => 'sometimes|required|in:block,unblock',
            ]);

            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'customer' => $customer,
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
            Customer::destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Product not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}