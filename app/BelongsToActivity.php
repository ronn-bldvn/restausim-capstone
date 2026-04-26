<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToActivity
{
    protected static function bootBelongsToActivity()
    {
        // Automatically set user_id and activity_id on create
        static::creating(function ($model) {
            if (Auth::check() && !$model->user_id) {
                $model->user_id = Auth::id();
            }

            if (session()->has('activity_id') && !$model->activity_id) {
                $model->activity_id = session('activity_id');
            }
        });

        // Global scope for queries
        static::addGlobalScope('activity', function (Builder $builder) {
            if (session()->has('activity_id')) {
                $builder->where('activity_id', session('activity_id'));
            }
        
            // static::addGlobalScope('section', function (Builder $builder) {
            //     if (session()->has('section_id')) {
            //         $builder->where('section_id', session('section_id'));
            //     }

            // if (Auth::check()) {
            //     $builder->where('user_id', Auth::id());
            // }
        });
    }
}
