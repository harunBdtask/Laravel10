<?php

namespace App\Helpers;

class UniqueCodeGenerator
{
    public static function generate($prefix, $id, $length = 6): string
    {
        return getPrefix() . $prefix . '-' . date('y') . '-' . str_pad($id, $length, '0', STR_PAD_LEFT);
    }
}
