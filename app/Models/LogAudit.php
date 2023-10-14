<?php

namespace App\Models;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class LogAudit extends Model
{
    protected $table = 'log_audits';
    protected $primaryKey = 'id';
    protected $fillable = [
        'date',
        'user_id',
        'event',
        'module',
        'auditable_id',
        'auditable_type',
        'history',
        'meta'
    ];

    protected $casts = [
        'meta' => Json::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }
}
