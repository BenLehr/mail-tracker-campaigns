<?php

namespace benlehr\MailTracker\Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use benlehr\MailTracker\Model\SentEmail;
use benlehr\MailTracker\RecordBounceJob;
use benlehr\MailTracker\RecordDeliveryJob;
use benlehr\MailTracker\RecordComplaintJob;
use benlehr\MailTracker\RecordLinkClickJob;
use benlehr\MailTracker\Events\LinkClickedEvent;

class RecordLinkClickJobTest extends SetUpTest
{
    /**
     * @test
     */
    public function it_records_clicks_to_links()
    {
        Event::fake();
        $track = \benlehr\MailTracker\Model\SentEmail::create([
                'hash' => Str::random(32),
            ]);
        $clicks = $track->clicks;
        $clicks++;
        $redirect = 'http://'.Str::random(15).'.com/'.Str::random(10).'/'.Str::random(10).'/'.rand(0, 100).'/'.rand(0, 100).'?page='.rand(0, 100).'&x='.Str::random(32);
        $job = new RecordLinkClickJob($track, $redirect, '127.0.0.1');

        $job->handle();

        Event::assertDispatched(LinkClickedEvent::class, function ($e) use ($track) {
            return $track->id == $e->sent_email->id &&
                $e->ip_address == '127.0.0.1';
        });
        $this->assertDatabaseHas('sent_emails_url_clicked', [
                'url' => $redirect,
                'clicks' => 1,
            ]);
    }
}
