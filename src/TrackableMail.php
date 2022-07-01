<?php

namespace benlehr\MailTracker;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrackableMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $campaignId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($campaignId = NULL)
    {
        $this->campaignId = $campaignId;

    }


    public function build()
    {

        if($this->campaignId) {
            $this->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader(
                    'X-Campaign-ID', $this->campaignId
                );
            });
        }

    }

}
