<?php

namespace benlehr\MailTracker\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use benlehr\MailTracker\Model\SentEmail;

class ViewEmailEvent implements ShouldQueue
{
    use SerializesModels;

    public $sent_email;
    public $ip_address;

    /**
     * Create a new event instance.
     *
     * @param  sent_email  $sent_email
     * @return void
     */
    public function __construct(SentEmail $sent_email, $ip_address)
    {
        $this->sent_email = $sent_email;
        $this->ip_address = $ip_address;
    }
}
