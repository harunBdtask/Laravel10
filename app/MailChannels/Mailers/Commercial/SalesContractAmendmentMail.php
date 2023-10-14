<?php

namespace App\MailChannels\Mailers\Commercial;

use App\MailChannels\Contracts\MailingChannelContract;
use App\MailChannels\MailDTO;
use Carbon\Carbon;

class SalesContractAmendmentMail implements MailingChannelContract
{
    public $amendData=null;
    public $email=null;

    public function __construct($amendData, $email)
    {
        $this->amendData = $amendData;
        $this->email = $email;
    }
    public function provideData(): array
    {
        return [
            'title' => 'Sales Contract Amendment',
            'sc_amends' => [$this->amendData]
        ];
    }

    public function view(): string
    {
        return 'auto_mail.commercial.sc_amend_mail_body';
    }

    public function subject(): string
    {
        return 'Sales Contract Amendment';
    }

    public function receivers(): array
    {
        return [$this->email];
    }
}
