<?php
namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Pest\Plugins\Only;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function all($filters = [])
    {
        $orders = $this->orderRepository->all($filters);
        $result = [
            'meta' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
            ],
            'orders' => $orders->items(),
        ];
        return $result;
    }
    public function update($id, $filters)
    {
        return $this->orderRepository->update($id, $filters);
    }
    public function find($id)
    {
        return $this->orderRepository->find($id);
    }
}