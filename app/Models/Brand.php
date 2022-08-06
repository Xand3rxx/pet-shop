<?php

namespace App\Models;

use App\Traits\GenerateUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, GenerateUUID;

    /**
     * To support mass assignable attributes from a validated request.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     * Set default parameters when a new brand is to be created.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($brand) {
            $brand->slug  = (string) \Illuminate\Support\Str::slug($brand['title']);
        });
    }

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
}
