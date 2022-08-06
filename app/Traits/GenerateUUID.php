<?php

namespace App\Traits;

trait GenerateUUID
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) \Illuminate\Support\Str::uuid()->toString();
        });
    }
}
