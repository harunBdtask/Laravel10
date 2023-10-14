<?php


namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

trait ModelCommonTrait
{
    public static function bootModelCommonTrait()
    {
        static::creating(function ($model) {
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = Auth::id();
            }

            if (!$model->factory_id && in_array('factory_id', $model->getFillable())) {
                $model->factory_id = factoryId();
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                $model->updated_by = Auth::id();
            }
        });

        static::saving(function ($model) {
            if (!$model->factory_id && in_array('factory_id', $model->getFillable())) {
                $model->factory_id = factoryId();
            }
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->created_by = Auth::id();
            }
            if (in_array('updated_by', $model->getFillable())) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => Auth::id(),
                    ]);
            }
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
