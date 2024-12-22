<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts($validated)
    {
        $products = $this->productRepository->getAllProducts($validated);
        $result = [
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'products' => $products->items()
        ];
        return $result;
    }

    public function getProductById($id)
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct($validated)
    {
        return $this->productRepository->createProduct($validated);
    }

    public function updateProduct($id, $validated)
    {
        return $this->productRepository->updateProduct($id, $validated);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->deleteProduct($id);
    }
}
