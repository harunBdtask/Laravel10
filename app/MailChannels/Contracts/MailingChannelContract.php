<?php

namespace App\MailChannels\Contracts;

interface MailingChannelContract
{
    public function provideData();

    public function view(): string;

    public function subject(): string;

    public function receivers(): array;
}
