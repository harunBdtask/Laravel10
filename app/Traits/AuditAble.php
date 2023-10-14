<?php

namespace App\Traits;

use App\Facades\AuditFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait AuditAble
{

    public static function bootAuditAble()
    {

        if (!config('app.audit')) {
            return;
        }


        static::created(function ($model) {
            $tableName = $model->getTable();
            $event = Auth::user()->screen_name . " Created " . Str::replace('_', ' ', Str::ucfirst($tableName));
            $moduleName = $model->moduleName() ?? '';
            $path = $model->path() ?? '';
            AuditFacade::setEvent($event)
                ->setModule($moduleName)
                ->setPath($path)
                ->setAuditId($model->getKey())
                ->setAuditType(static::class)
                ->setHistory('created')
                ->setTableName($tableName)
                ->setNewValue($model->attributesToArray())
                ->attach();
        });

        static::saving(function ($model) {
            if (!$model->id || !$model->isDirty()) {
                return;
            }
            $tableName = $model->getTable();
            $event = Auth::user()->screen_name . ($model->id ? ' Updated ' : ' Created ') . Str::replace('_', ' ', Str::ucfirst($tableName));
            $moduleName = $model->moduleName() ?? '';
            $path = $model->path() ?? '';
            AuditFacade::setEvent($event)
                ->setModule($moduleName)
                ->setPath($path)
                ->setAuditId($model->getKey())
                ->setAuditType(static::class)
                ->setHistory($model->id ? 'updated' : 'created')
                ->setTableName($tableName)
                ->setNewValue($model->attributesToArray())
                ->setOldValue($model->id ? $model->getOriginal() : null)
                ->attach();
        });

        static::deleted(function ($model) {
            $tableName = $model->getTable();
            $event = Auth::user()->screen_name . " Deleted " . Str::replace('_', ' ', Str::ucfirst($tableName));
            $moduleName = $model->moduleName() ?? '';
            $path = $model->path() ?? '';
            AuditFacade::setEvent($event)
                ->setModule($moduleName)
                ->setPath($path)
                ->setAuditId($model->getKey())
                ->setAuditType(static::class)
                ->setHistory('deleted')
                ->setTableName($tableName)
                ->setNewValue($model->attributesToArray())
                ->attach();
        });
    }


}
