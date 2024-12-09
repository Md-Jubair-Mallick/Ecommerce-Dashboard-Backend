<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
 * - Features:
 * -- List Customers: Fetch customers with pagination.
 * -- Customer Details: View detailed information about a customer, including their order history.
 * -- Add/Edit/Delete Customer: Admins can manage customer profiles.
 * 
 * - Validation Rules: Enforce rules for customer email, phone number, etc.
 */
