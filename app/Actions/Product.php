<?php

namespace App\Actions;

use App\Actions\File;
use App\Models\Product as ProductModel;

class Product
{
    use File;

    /**
     * Create or update product record in storage.
     *
     * @param array  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(array $request)
    {
        // Set `hasBeenCreated` to false before DB transaction
        (bool) $hasBeenCreated = false;

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$hasBeenCreated) {
            $product = ProductModel::updateOrCreate([
                'uuid'    => request()->uuid,
            ], [
                'category_uuid' => $request['category_uuid'],
                'title'         => $request['title'],
                'price'         => ProductModel::removeComma($request['price']),
                'description'   => $request['description'],
                'metadata'      => request()->except('image'),
            ]);

            if (request()->file('image')) {
                $product->update([
                    'metadata'  => array_merge(['image' => $this->createFileRecord(request(), 'image')['uuid']], $product['metadata'])
                ]);
            }

            $hasBeenCreated = true;
        }, 3); // Try 3 times before reporting an error

        return $hasBeenCreated;
    }
}
