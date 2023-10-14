<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

trait BelongsToSupplier
{
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }
}
