<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploader
{
    /**
     * Extract metadata from uploaded file.
     *
     * @param object  $file
     * @param string  $path
     *
     * @return array  $file
     */
    public function fileMetadata($file, $path)
    {
        if ($file) {
            $fileName = Str::uuid().'.'. $file->getClientOriginalExtension();
            Storage::disk('public')->put($path . $fileName, File::get($file));

            return $file = [
                'name' => $file->getClientOriginalName(),
                'path' => 'storage/' . $path . $fileName,
                'size' => $this->getFileSize($file),
                'type' => $file->getClientOriginalExtension()
            ];
        }
    }

    /**
     * Get the formmated size of the uploaded file.
     *
     * @param object  $file
     * @return string  $size
     */
    public function getFileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}
