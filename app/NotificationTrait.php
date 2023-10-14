<?php


namespace App;

use App\Notifications\UserNotification;
use Skylarksoft\Systemsettings\Models\User;

trait NotificationTrait
{
    public function notificationSend($message, $information, $user = '')
    {
        $users = User::where('role_id', 2)->get();
        foreach ($users as $singleUser) {
            $singleUser ->notify(new UserNotification($message, $information, $user));
        }
    }
}
