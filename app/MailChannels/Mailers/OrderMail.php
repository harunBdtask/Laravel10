<?php

namespace App\MailChannels\Mailers;

use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;

class OrderMail implements MailingChannelContract
{

    public function subject(): string
    {
        return 'Order Report Generated';
    }


    public function receivers(): array
    {
        return MailDTO::getReceivers('order_entry');
    }

    public function provideData(): array
    {
        return [
            'name' => 'Hello From The Other Side'
        ];
    }

    public function view(): string
    {
        return 'auto_mail.order.create';
    }
}
