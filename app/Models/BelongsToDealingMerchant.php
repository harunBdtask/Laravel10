<?php


namespace App\Models;

use SkylarkSoft\GoRMG\SystemSettings\Models\User;

trait BelongsToDealingMerchant
{
    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'dealing_merchant_id')->withDefault([
            'screen_name' => 'N/A',
        ]);
    }
}
