<?php

namespace App\Traits;


trait UpdatableAndCreatable
{
    public static function bootUpdatableAndCreatable()
    {
        if (auth()->check()) {
            static::creating(function ($model) {
                $model->created_by_id = auth()->id();
                $model->circle_id = auth()->user()->circles->first()->id;
                // if (auth()->user()->hasRole(['Circle'])) {
                // }
            });

            static::updating(function ($model) {
                $model->updated_by_id = auth()->id();
            });
        }
    }
}
