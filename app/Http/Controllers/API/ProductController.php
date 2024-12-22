<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
            $products = $this->productService->getAllProducts($req);
            return ResponseHelper::success($products, 'Products fetched successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Error fetching products', 500);
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
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_name' => 'required|string|max:100',
        ]);
            $product = $this->productService->createProduct($validated);
            return ResponseHelper::success($product, 'Product created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Error creating product', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->productService->getProductById($id);
            return ResponseHelper::success($product, 'Product fetched successfully', 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error('Product not found', 404);
        } catch (\Exception $e) {
            return ResponseHelper::error('Error fetching product', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $req)
    {
        try {
            $validated = $req->validate([
                'name' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'description' => 'sometimes|required|string',
                'stock' => 'sometimes|required|integer'
            ]);
            $product = $this->productService->updateProduct($id, $validated);
            return ResponseHelper::success($product, 'Product updated successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::error('Error updating product', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->productService->deleteProduct($id);
            return ResponseHelper::success([], 'Product deleted successfully', 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error('Product not found', 404);
        } catch (\Exception $e) {
            return ResponseHelper::error('Error deleting product', 500);
        }
    }
}
