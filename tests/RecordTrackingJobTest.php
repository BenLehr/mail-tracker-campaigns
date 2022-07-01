<?php

namespace benlehr\MailTracker\Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use benlehr\MailTracker\Model\SentEmail;
use benlehr\MailTracker\RecordBounceJob;
use benlehr\MailTracker\RecordDeliveryJob;
use benlehr\MailTracker\RecordTrackingJob;
use benlehr\MailTracker\RecordComplaintJob;
use benlehr\MailTracker\RecordLinkClickJob;
use benlehr\MailTracker\Events\ViewEmailEvent;
use benlehr\MailTracker\Events\LinkClickedEvent;

class RecordTrackingJobTest extends SetUpTest
{
    /**
     * @test
     */
    public function it_records_views()
    {
        Event::fake();
        $track = \benlehr\MailTracker\Model\SentEmail::create([
                'hash' => Str::random(32),
            ]);
        $job = new RecordTrackingJob($track, '127.0.0.1');

        $job->handle();

        Event::assertDispatched(ViewEmailEvent::class, function ($e) use ($track) {
            return $track->id == $e->sent_email->id &&
                $e->ip_address == '127.0.0.1';
        });
        $this->assertDatabaseHas('sent_emails', [
                'id' => $track->id,
                'opens' => 1,
            ]);
    }
}
