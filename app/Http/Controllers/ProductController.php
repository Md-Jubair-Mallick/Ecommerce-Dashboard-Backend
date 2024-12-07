<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        try {
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
            if ($req->has('sort_by') && $req->has('sort_order')) {
                $query->orderBy($req->input('sort_by'), $req->input('sort_order', 'asc'));
            }

            // Paginate products
            $products = $query->paginate($req->input('per_page', 10));

            return response()->json(['success' => true, 'result' => $products], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        try {
            $validatedData = $req->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'category_name' => 'required|string',
                'stock' => 'required|integer',
            ]);

            $category = Category::firstOrCreate(['name' => $validatedData['category_name']]);

            $product = Product::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'description' => $validatedData['description'],
                'category_id' => $category->id,
                'stock' => $validatedData['stock'],
            ]);

            return response()->json(['success' => true, 'result' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'result' => $product,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        try {
            $validatedData = $req->validate([
                'name' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
                'description' => 'sometimes|required|string',
                'stock' => 'sometimes|required|integer'
            ]);

            $product = Product::updateOrCreate(
                ['id' => $req->id],
                $validatedData
            );

            return response()->json(['success' => true, 'result' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
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

/**
 * TODO
 * - Implement the logic for the ProductController
 * Features
 * - List Products: Fetch paginated and filtered lists of products.
 * - Add/Edit/Delete Product: Admins can manage products.
 * - Filtering and sorting by category, price, stock status, etc.
 */

/**
 * DONE
 * - Add validation for the store and update methods
 * - Add a edit method to edit an existing product
 * - Add a create method to create a new product
 * - [Normal] : Add a get all method to get all products
 * 
 * - [Filter] : Add a filter method to filter products
 * -- [Filter] : Add a filter method to fetch products by category
 * -- [Filter] : Add a filter method to fetch products by price[max, min]
 * - [Sort] : Add a sort method to sort products
 * - [Pagination] : Add a paginate method to paginate products
 * - Add a get method to get a product by id
 * -- Add a show method to display the product details
 * - Add a delete method to delete a product
 * -
 */
