<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

trait FactoryIdTrait
{
    protected static function bootFactoryIdTrait()
    {
        static::addGlobalScope('factoryId', function (Builder $builder) {
            if (Request::segment(1) != 'production-dashboard') {
                $table = $builder->getModel()->getTable();
                $builder->where(($table ? $table . '.' : '') . 'factory_id', factoryId());
            }
        });

        static::creating(function ($model) {
            $model->factory_id = factoryId();
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = userId();
            }
        });

        static::saving(function ($model) {
            $model->factory_id = factoryId();
            if (!$model->id) {
                $model->created_at = Carbon::now();
            }
            $model->updated_at = Carbon::now();
        });

        static::deleting(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => userId(),
                    ]);
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'updated_by' => userId(),
                ]);
            }
        });
    }
}
