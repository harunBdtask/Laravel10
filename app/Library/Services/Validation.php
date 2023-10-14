<?php 
namespace App\Library\Services;

use Illuminate\Database\Eloquent\Model;

/**
 * Documentation
 * ======================
 * Validation for Other Model Dependency
 * $id = Which row id will be deleted
 * $relations = [Model::CLass, ForeignKey]
 * )
 * 
 * if return false
 */

class Validation
{

    public static function check($id, $relation = [])
    {
        if($relation[0]::where($relation[1], $id)->exists())
        {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Validation for multi model
     * Use of multi Dimension array
     * $id = Which row id will be deleted
     *  $relations = array(
     *  [Model::CLass, ForeignKey],
     *  [Model::CLass, ForeignKey]
     * )
     */
    public static function checkAll($id, $relations = [])
    {
        foreach($relations as $item)
        {
            if($item[0]::where($item[1], $id)->exists())
            {
                return FALSE;
            }
        }
        return TRUE;
    }


    
}