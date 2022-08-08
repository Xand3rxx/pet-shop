<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Actions\CreateProduct;
use App\Actions\UpdateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->limit(100)->paginate(20);
        return (count($products))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => count($products) . ' product records found.',
                        'products' => $products->toArray(),
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'Sorry! No products records found',
                    'products' => [],
                ]
            ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Actions\CreateProduct  $productAction
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, CreateProduct $createProduct)
    {
        return ($createProduct->handle($request->validated()))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'Product was successfully created.',
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to create an product.',
                ]
            ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return (!is_null($product))
            ? response()->json(
                [
                    'data' =>    [
                        'product' => $product,
                    ]
                ],
                200
            )
            : response()->json(
                [
                    'data' => [
                        'message' => 'Sorry! Products not found.',
                    ]
                ],
                404
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Actions\UpdateProduct  $updateProduct
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, UpdateProduct $updateProduct, Product $product)
    {
        // Merge existing metadata volatile records with sanitized product update request
        $validated = [];

        if(!empty($product['metadata']['image']) && empty($request['image'])){
            $validated['image'] = $product['metadata']['image'];
        }

        if(!empty($product['metadata']['brand']) && empty($request['brand'])){
            $validated['brand'] = $product['metadata']['brand'];
        }

        return ($updateProduct->handle($product, array_merge($validated, $request->validated())))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'Product was successfully updated.',
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to update product.',
                ]
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return ($product->delete())
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'Product was successfully deleted.',
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to delete product.',
                ]
            ], 500);
    }
}
