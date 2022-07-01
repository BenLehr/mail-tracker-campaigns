<?php

namespace benlehr\MailTracker;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use benlehr\MailTracker\Model\SentEmail;
use benlehr\MailTracker\Events\ComplaintMessageEvent;

class RecordComplaintJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function retryUntil()
    {
        return now()->addDays(5);
    }

    public function handle()
    {
        $sent_email = SentEmail::where('message_id', $this->message->mail->messageId)->first();
        if ($sent_email) {
            $meta = collect($sent_email->meta);
            $meta->put('complaint', true);
            $meta->put('success', false);
            $meta->put('complaint_time', $this->message->complaint->timestamp);
            if (!empty($this->message->complaint->complaintFeedbackType)) {
                $meta->put('complaint_type', $this->message->complaint->complaintFeedbackType);
            }
            $meta->put('sns_message_complaint', $this->message); // append the full message received from SNS to the 'meta' field
            $sent_email->meta = $meta;
            $sent_email->save();

            foreach ($this->message->complaint->complainedRecipients as $recipient) {
                Event::dispatch(new ComplaintMessageEvent($recipient->emailAddress, $sent_email));
            }
        }
    }
}
