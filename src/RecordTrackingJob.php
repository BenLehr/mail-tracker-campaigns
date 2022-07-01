<?php

namespace benlehr\MailTracker;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use benlehr\MailTracker\Model\SentEmail;
use benlehr\MailTracker\Events\ViewEmailEvent;
use benlehr\MailTracker\Events\LinkClickedEvent;
use benlehr\MailTracker\Model\SentEmailUrlClicked;
use benlehr\MailTracker\Events\EmailDeliveredEvent;

class RecordTrackingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $sentEmail;
    public $ipAddress;

    public function __construct($sentEmail, $ipAddress)
    {
        $this->sentEmail = $sentEmail;
        $this->ipAddress = $ipAddress;
    }

    public function retryUntil()
    {
        return now()->addDays(5);
    }

    public function handle()
    {
        $this->sentEmail->opens++;
        $this->sentEmail->save();
        Event::dispatch(new ViewEmailEvent($this->sentEmail, $this->ipAddress));
    }
}
