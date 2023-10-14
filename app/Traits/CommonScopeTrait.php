<?php

namespace App\Traits;

trait CommonScopeTrait
{


    public function scopeWhereBelongsToBuyer($buyerId, $query)
    {
        if (!$buyerId) {
            return $query;
        }

        return $query->where('buyer_id', $buyerId);

    }
}