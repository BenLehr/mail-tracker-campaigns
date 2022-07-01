<?php

namespace benlehr\MailTracker\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use benlehr\MailTracker\Model\SentEmail;

class EmailSentEvent implements ShouldQueue
{
    use SerializesModels;

    public $sent_email;

    /**
     * Create a new event instance.
     *
     * @param  sent_email  $sent_email
     * @return void
     */
    public function __construct(SentEmail $sent_email)
    {
        $this->sent_email = $sent_email;
    }
}
