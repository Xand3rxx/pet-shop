<?php

namespace App\Actions;

use App\Actions\File;

class UpdateProduct
{
    use File;

    /**
     * Create or update product record in storage.
     *
     * @param object  $product
     * @param array  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(object $product, array $request)
    {
        // Set `hasBeenUpdated` to false before DB transaction
        (bool) $hasBeenUpdated = false;

        \Illuminate\Support\Facades\DB::transaction(function () use ($product, $request, &$updatedProduct, &$hasBeenUpdated) {
            $product = $product->update([
                'category_uuid' => $request['category_uuid'],
                'title'         => $request['title'],
                'price'         => \App\Models\Product::removeComma($request['price']),
                'description'   => $request['description'],
                'metadata'      => $request,
            ]);

            if (request()->file('image')) {
                $product->update([
                    'metadata'  => array_merge(['image' => $this->createFileRecord(request(), 'image')['uuid']], $product['metadata'])
                ]);
            }

            $hasBeenUpdated = true;
        }, 3); // Try 3 times before reporting an error

        return [
            'success' => $hasBeenUpdated,
            'product' => $product->fresh()
        ];
    }
}
