<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class UIDModel extends Model
{
    abstract public static function getConfig(): array;

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $config = static::getConfig();
            $fieldName = $config['field'] ?? 'uniq_id';
            $modelAbbr = $config['abbr'];
            $model->{$fieldName} = getPrefix() . $modelAbbr . '-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }
}
