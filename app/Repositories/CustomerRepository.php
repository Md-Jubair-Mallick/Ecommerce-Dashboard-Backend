<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function index($filters)
    {
        $query = Customer::query();

        // Filters by status.
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $per_page = $filters['per_page'] ?? 10;
        $customer = $query->paginate($per_page);

        return $customer;
    }

    public function store($validated)
    {
        $customer = Customer::create($validated);
        return $customer;
    }

    public function show($id)
    {
        return Customer::with('orders')->find($id);
    }

    public function update($id, $validated)
    {
        $customer = Customer:: findOrFail($id);
        $customer->update($validated);
        return $customer;
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return $customer;
    }
}