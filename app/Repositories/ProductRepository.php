<?php

namespace App\Repositories;

use App\Models\Product;


class ProductRepository
{
    public function getAllProducts($req)
    {
        
            // Start building the query
            $query = Product::query();

            // Filter by category
            if ($req->has('category_name')) {
                $query->whereHas('category', function ($query) use ($req) {
                    $query->where('name', $req->category_name);
                });
            }

            // search by category and product name
            if ($req->has('search')) {
                $query->where(function ($query) use ($req) {
                    $query->where('name', 'like', '%' . $req->search . '%')
                        ->orWhereHas('category', function ($query) use ($req) {
                            $query->where('name', 'like', '%' . $req->search . '%');
                        });
                });
            }

            // Filter products by price
            if ($req->has('price_max') || $req->has('price_min')) {
                $max = $req->input('price_max', PHP_INT_MAX);
                $min = $req->input('price_min', PHP_INT_MIN);
                $query->whereBetween('price', [$min, $max]);
            }

            // fetch products by sorting
            $sortableFields = ['name', 'price', 'created_at'];
            if ($req->has('sort_by') && in_array($req->input('sort_by'), $sortableFields)) {
                $query->orderBy($req->input('sort_by'), $req->input('sort_order', 'asc'));
            }

            // Paginate products
            $products = $query->paginate($req->input('per_page', 10));

            return response()->json(['success' => true, 'result' => $products], 200);
        
        return Product::all();
    }

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProduct($id, array $data)
    {
        return Product::findOrFail($id)->update($data);
    }

    public function deleteProduct($id)
    {
        return Product::findOrFail($id)->delete();
    }
}
