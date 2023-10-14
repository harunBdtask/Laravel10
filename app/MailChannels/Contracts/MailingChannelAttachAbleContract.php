<?php

namespace App\MailChannels\Contracts;

interface MailingChannelAttachAbleContract
{
    public function attachment(): array;
}
