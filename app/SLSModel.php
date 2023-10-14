<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class SLSModel extends Model
{
    public static function pushCriteria(Criteria $criteria)
    {
        return static::where($criteria->execute());
    }
}
