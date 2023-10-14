<?php

namespace App\MailChannels;

use App\Jobs\MailChannelJob;
use App\Mail\InformingMail;
use App\MailChannels\Contracts\MailingChannelContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailConfiguration;

class MailChannel
{
    public function for(MailingChannelContract $mailable): MailChannel
    {
        $mailingChannel = $this->mailable($mailable);
        if ($this->isMailEnabled()) {
            foreach ($mailable->receivers() as $mail) {
                MailChannelJob::dispatch($mail, $mailingChannel);
            }
        }
        return $this;
    }

    private function isMailEnabled()
    {
        return MailConfiguration::query()->first()['is_enabled'] ?? false;
    }

    private function mailable(MailingChannelContract $mailable): InformingMail
    {
        return new InformingMail($mailable);
    }
}
