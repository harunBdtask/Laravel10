<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    /*
     * data column accept an array.
     * must include url, title, module (required) in this array as key, value pair.
     * and your required key, value.
     *
     * title is that you want to show in notification bar.
     */
}
