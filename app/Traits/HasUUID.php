<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    /**
     * Automatically set the UUID upon creation.
     */
    public static function bootHasUUID()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
