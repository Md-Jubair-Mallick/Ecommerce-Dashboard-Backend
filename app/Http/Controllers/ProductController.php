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
            $sortableFields = ['name', 'price', 'created_at'];
            if ($req->has('sort_by') && in_array($req->input('sort_by'), $sortableFields)) {
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
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_name' => 'required|string|max:100',                
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
