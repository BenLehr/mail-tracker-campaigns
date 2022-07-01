<?php

namespace benlehr\MailTracker\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use benlehr\MailTracker\Model\SentEmail;

class TransientBouncedMessageEvent implements ShouldQueue
{
    use SerializesModels;

    public $email_address;
    public $sent_email;
    public $bounce_sub_type;
    public $diagnostic_code;

    /**
     * Create a new event instance.
     *
     * @param  email_address  $email_address
     * @param  sent_email  $sent_email
     * @return void
     */
    public function __construct($email_address, $bounce_sub_type, $diagnostic_code, SentEmail $sent_email = null)
    {
        $this->email_address = $email_address;
        $this->sent_email = $sent_email;
        $this->bounce_sub_type = $bounce_sub_type;
        $this->diagnostic_code = $diagnostic_code;
    }
}
