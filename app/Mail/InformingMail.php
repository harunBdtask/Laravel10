<?php

namespace App\Mail;

use App\MailChannels\Contracts\MailingChannelAttachAbleContract;
use App\MailChannels\Contracts\MailingChannelContract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformingMail extends Mailable
{
    use Queueable, SerializesModels;

    private $mailingChannel;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MailingChannelContract $mailingChannel)
    {
        $this->mailingChannel = $mailingChannel;
        $this->data = $mailingChannel->provideData();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): InformingMail
    {

        $this
            ->from(config('mail.mailers.mail_channel.from.address'), config('mail.mailers.mail_channel.from.name'))
            ->subject($this->mailingChannel->subject());

        if ($this->mailingChannel instanceof MailingChannelAttachAbleContract) {
            foreach ($this->mailingChannel->attachment() as $attachment) {
                $this->attach($attachment);
            }

        }

        return $this->view($this->mailingChannel->view());
    }
}
