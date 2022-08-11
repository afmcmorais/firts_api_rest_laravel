<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\API\ApiError;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        return response()->json($this->product->paginate(10));
    }

    public function show($id)
    {
        if(!$product = $this->product->find($id)){
            return response()->json(ApiError::errorMessage('Product not found', 4040), 404);
        }
        $data = ['data' => $this->product->find($id)];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $productData = $request->all();
            $this->product->create($productData);

            $return = ['data' => ['message' => 'Product created successfully']];
            return response()->json($return, 201);

        } catch (\Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Error while creating product', 1010), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $productData = $request->all();
            $this->product->find($id)->update($productData);

            $return = ['data' => ['message' => 'Product updated successfully']];
            return response()->json($return, 201);

        } catch (\Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }
            return response()->json(ApiError::errorMessage('Error while updating product', 1011), 500);
        }
    }

    public function delete(Product $id)
    {
        try {
            $id->delete();
            return response()->json(['data' => ['message' => 'Product deleted successfully']], 200);
        } catch (\Exception $e) {
            if(config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()->json(ApiError::errorMessage('Error while deleting product', 1012), 500);
        }
    }
}
