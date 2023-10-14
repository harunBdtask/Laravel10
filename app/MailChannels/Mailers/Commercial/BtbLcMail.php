<?php

namespace App\MailChannels\Mailers\Commercial;

use Carbon\Carbon;
use App\MailChannels\MailDTO;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use App\MailChannels\Contracts\MailingChannelContract;

class BtbLcMail implements MailingChannelContract
{
    public $itemId=null;
    public $teamLeaderId=null;

    public function __construct($itemId, $teamLeaderId)
    {
        $this->itemId = $itemId;
        $this->teamLeaderId = $teamLeaderId;

    }
    public function provideData(): array
    {
        $btbInfo = B2BMarginLC::query()
        ->where('id', $this->itemId)
        ->with(['lienBank','item','supplier'])
        ->get();

        return   [
            'title' => 'BTB LC',
            'btblc' => $btbInfo
        ];
    }

    public function view(): string
    {
        return 'auto_mail.commercial.btblc_mail_body';
    }

    public function subject(): string
    {
        return 'BTB LC';
    }

    public function receivers(): array
    {
        $userInfo = User::where('id', $this->teamLeaderId)->first();
        return [$userInfo->email];
    }
}
