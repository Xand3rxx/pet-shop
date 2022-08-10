<?php

namespace App\Models;

use App\Traits\GenerateUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, GenerateUUID;

    /**
     * To support mass assignable attributes from a validated request.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

     /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['category', 'file', 'brand'];
    // protected $with = ['category'];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('uuid', $value)->firstOrFail();
    }

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
    // dd($this->metadata);

        return $this->hasOne(Category::class, 'uuid', 'category_uuid');
    }

    /**
     * Get the brand associated with the product.
     */
    public function brand()
    {
        return $this->hasOne(Brand::class, 'uuid', 'metadata["brand"]');
    }

    /**
     * Get the file associated with the product.
     */
    public function file()
    {
        return $this->hasOne(File::class, 'uuid', 'metadata["image"]');
    }

    /**
     * Remove comma from number format without removing decimal point.
     */
    public static function removeComma($value)
    {
        return floatval(preg_replace('/[^\d.]/', '', $value));
    }
}
