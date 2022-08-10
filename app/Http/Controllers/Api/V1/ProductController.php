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
     * @OA\Get (
     *       path="/api/v1/products",
     *       operationId="listProducts",
     *       tags={"Products"},
     *       summary="Display all products",
     *       description="Returns products information.",
     *       security={ {"bearer": {} }},
     *       @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="1 product record(s) found."),
     *            @OA\Property(
     *              type="array",
     *              property="products",
     *              @OA\Items(
     *              type="object",
     *              @OA\Property(property="category_uuid", type="string", example="3177e824-0efb-4873-adad-6b8ab82f738c"),
     *              @OA\Property(property="title", type="string", example="Sweet dog treats"),
     *              @OA\Property(property="price", type="number", example=22500),
     *              @OA\Property(property="description", type="string", example="A fantastic treat for all dog breeds."),
     *              @OA\Property(
     *                          description="Product metadata",
     *                          property="metadata",
     *                          type="object",
     *                               @OA\Property(
     *                                   property="image",
     *                                   type="string",
     *                                   example="4d469c9a-0673-4510-addd-e4a4db2d4e02"
     *                               ),
     *                               @OA\Property(
     *                                   property="brand",
     *                                   type="string",
     *                                   example="597370e5-4804-4852-bd32-29d65b3b679a"
     *                               )
     *              ),
     *              @OA\Property(property="uuid", type="string", example="0f2de771-25dc-488d-81f6-95ba99bfebc8"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="22022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="id", type="number", example=1)
     *          ),
     *         ),
     *        )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No products records found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
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
                        'message' => count($products) . ' product record(s) found.',
                        'products' => $products->toArray(),
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'No products records found.',
                    'products' => [],
                ]
            ], 404);
    }

    /**
     * @OA\Post (
     *       path="/api/v1/products",
     *       operationId="storeProduct",
     *       tags={"Products"},
     *       summary="Store new product",
     *       description="Returns the created product information",
     *       security={ {"bearer": {} }},
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"category_uuid", "title", "price", "description", "metadata"},
     *                      @OA\Property(
     *                          description="Category UUID",
     *                          property="category_uuid",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Product title",
     *                          property="title",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Product price",
     *                          property="price",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          description="Product description",
     *                          property="description",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Product metadata",
     *                          property="metadata",
     *                          type="object",
     *                               @OA\Property(
     *                                   description="Image UUID",
     *                                   property="image",
     *                                   type="string"
     *                               ),
     *                               @OA\Property(
     *                                   description="Brand UUID",
     *                                   property="brand",
     *                                   type="string"
     *                               )
     *                      ),
     *                 example={
     *                     "category_uuid": "3177e824-0efb-4873-adad-6b8ab82f738c",
     *                     "title": "Sweet dog treats",
     *                     "price": 25500,
     *                     "description": "A fantastic treat for all dog breeds."
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Product was successfully created."),
     *              @OA\Property(property="category_uuid", type="string", example="3177e824-0efb-4873-adad-6b8ab82f738c"),
     *              @OA\Property(property="title", type="string", example="Sweet dog treats"),
     *              @OA\Property(property="price", type="number", example=22500),
     *              @OA\Property(property="description", type="string", example="A fantastic treat for all dog breeds."),
     *              @OA\Property(
     *                          description="Product metadata",
     *                          property="metadata",
     *                          type="object",
     *                               @OA\Property(
     *                                   property="image",
     *                                   type="string",
     *                                   example="4d469c9a-0673-4510-addd-e4a4db2d4e02"
     *                               ),
     *                               @OA\Property(
     *                                   property="brand",
     *                                   type="string",
     *                                   example="597370e5-4804-4852-bd32-29d65b3b679a"
     *                               )
     *              ),
     *              @OA\Property(property="uuid", type="string", example="0f2de771-25dc-488d-81f6-95ba99bfebc8"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="22022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="id", type="number", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An error occurred while trying to create a product."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Actions\CreateProduct  $productAction
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, CreateProduct $createProduct)
    {
        return ($product = $createProduct->handle($request->validated()))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'Product was successfully created.',
                        'product' => $product['product'],
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to create a product.',
                ]
            ], 400);
    }

    /**
     * @OA\Get (
     *       path="/api/v1/products/{uuid}",
     *       operationId="showProduct",
     *       tags={"Products"},
     *       summary="Show product information",
     *       description="Returns product information with existing UUID.",
     *       security={ {"bearer": {} }},
     *       @OA\Parameter(
     *           in="path",
     *           description="Product UUID",
     *           name="uuid",
     *           @OA\Schema(type="string"),
     *           required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="category_uuid", type="string", example="3177e824-0efb-4873-adad-6b8ab82f738c"),
     *              @OA\Property(property="title", type="string", example="Sweet dog treats"),
     *              @OA\Property(property="price", type="number", example=22500),
     *              @OA\Property(property="description", type="string", example="A fantastic treat for all dog breeds."),
     *              @OA\Property(
     *                          description="Product metadata",
     *                          property="metadata",
     *                          type="object",
     *                               @OA\Property(
     *                                   property="image",
     *                                   type="string",
     *                                   example="4d469c9a-0673-4510-addd-e4a4db2d4e02"
     *                               ),
     *                               @OA\Property(
     *                                   property="brand",
     *                                   type="string",
     *                                   example="597370e5-4804-4852-bd32-29d65b3b679a"
     *                               )
     *              ),
     *              @OA\Property(property="uuid", type="string", example="0f2de771-25dc-488d-81f6-95ba99bfebc8"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="22022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="id", type="number", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Product Not Found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
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
                        'message' => 'Product not found.',
                    ]
                ],
                404
            );
    }

    /**
     * @OA\Put (
     *       path="/api/v1/products/{uuid}",
     *       operationId="updateProduct",
     *       tags={"Products"},
     *       summary="Update a product record",
     *       description="Update product information with existing UUID.",
     *       security={ {"bearer": {} }},
     *       @OA\Parameter(
     *           in="path",
     *           description="Product UUID",
     *           name="uuid",
     *           @OA\Schema(type="string"),
     *           required=true
     *      ),
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"category_uuid", "title", "price", "description", "metadata"},
     *                      @OA\Property(
     *                          description="Category UUID",
     *                          property="category_uuid",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Product title",
     *                          property="title",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Product price",
     *                          property="price",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          description="Product description",
     *                          property="description",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Product metadata",
     *                          property="metadata",
     *                          type="object",
     *                               @OA\Property(
     *                                   description="Image UUID",
     *                                   property="image",
     *                                   type="string"
     *                               ),
     *                               @OA\Property(
     *                                   description="Brand UUID",
     *                                   property="brand",
     *                                   type="string"
     *                               )
     *                      ),
     *                 example={
     *                     "category_uuid": "3177e824-0efb-4873-adad-6b8ab82f738c",
     *                     "title": "Sweet dog treats",
     *                     "price": 25500,
     *                     "description": "A fantastic treat for all dog breeds."
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Product was successfully updated."),
     *              @OA\Property(property="category_uuid", type="string", example="3177e824-0efb-4873-adad-6b8ab82f738c"),
     *              @OA\Property(property="title", type="string", example="Sweet dog treats"),
     *              @OA\Property(property="price", type="number", example=22500),
     *              @OA\Property(property="description", type="string", example="A fantastic treat for all dog breeds."),
     *              @OA\Property(
     *                          description="Product metadata",
     *                          property="metadata",
     *                          type="object",
     *                               @OA\Property(
     *                                   property="image",
     *                                   type="string",
     *                                   example="4d469c9a-0673-4510-addd-e4a4db2d4e02"
     *                               ),
     *                               @OA\Property(
     *                                   property="brand",
     *                                   type="string",
     *                                   example="597370e5-4804-4852-bd32-29d65b3b679a"
     *                               )
     *              ),
     *              @OA\Property(property="uuid", type="string", example="0f2de771-25dc-488d-81f6-95ba99bfebc8"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="22022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="id", type="number", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An error occurred while trying to update product."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Actions\UpdateProduct  $updateProduct
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, UpdateProduct $updateProduct, Product $product)
    {
        //  Merge existing metadata volatile records with sanitized product update request
        $validated = [];

        if (!empty($product['metadata']['image']) && empty($request['image'])) {
            $validated['image'] = $product['metadata']['image'];
        }

        if (!empty($product['metadata']['brand']) && empty($request['brand'])) {
            $validated['brand'] = $product['metadata']['brand'];
        }

        return ($product = $updateProduct->handle($product, array_merge($validated, $request->validated())))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'Product was successfully updated.',
                        'product' => $product['product'],
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to update product.',
                ]
            ], 400);
    }

    /**
     * @OA\Delete (
     *       path="/api/v1/products/{uuid}",
     *       operationId="deleteProduct",
     *       tags={"Products"},
     *       summary="Delete product record",
     *       description="Delete product information with existing UUID.",
     *       security={ {"bearer": {} }},
     *       @OA\Parameter(
     *           in="path",
     *           description="Product UUID",
     *           name="uuid",
     *           @OA\Schema(type="string"),
     *           required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product was successfully deleted."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An error occurred while trying to delete product."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Product Not Found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
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
            ], 400);
    }
}
