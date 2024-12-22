<?php
namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index($filters)
    {
        $customers = $this->customerRepository->index($filters);
        $result = [
            'customers' => $customers->items(),
            'pagination' => [
                'total' => $customers->total(),
                'per_page' => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem(),
            ],
        ];
        return $result;
    }

    public function store($validated)
    {
        return $this->customerRepository->store($validated);
    }

    public function show($id)
    {
        return $this->customerRepository->show($id);
    }

    public function update($id, $validated)
    {
        return $this->customerRepository->update($id, $validated);
    }

    public function destroy($id)
    {
        return $this->customerRepository->destroy($id);
    }
}