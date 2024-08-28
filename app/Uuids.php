<?php
/**
 * Created by PhpStorm.
 * User: Angelo
 * Date: 20/06/2018
 * Time: 1:39 PM
 */

namespace App;


use Ramsey\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $uuid1 = Uuid::uuid1();

            $model->{$model->getKeyName()} = $uuid1->toString();
        });
    }
}