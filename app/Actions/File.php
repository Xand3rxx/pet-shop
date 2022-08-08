<?php

namespace App\Actions;

use App\Traits\FileUploader;

trait File
{
    use FileUploader;

    /**
     * Create a new file record in storage.
     *
     * @param object  $request
     * @return \Illuminate\Http\Response
     */
    public function createFileRecord(object $request)
    {
        if ($file = $request->file('avatar')) {
            $data = $this->fileMetadata($file, 'pet-shop/');
            return \App\Models\File::create([
                'name'  => $data['name'],
                'path'  => $data['path'],
                'size'  => $data['size'],
                'type'  => $data['type'],
            ]);
        }
    }
}
