<?php

namespace benlehr\MailTracker\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use benlehr\MailTracker\Console\MigrateRecipients;
use benlehr\MailTracker\Model\SentEmail;

class MigrateRecipientsTest extends SetUpTest
{
    /**
     * @test
     */
    public function it_converts_existing_recipient_values()
    {
        Schema::connection((new SentEmail())->getConnectionName())->table('sent_emails', function (Blueprint $table) {
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
        });
        SentEmail::unguard();
        $tracker = SentEmail::create([
            'hash' => 'email-hash',
            'sender' => 'Sender Dude <sender@example.com>',
            'recipient' => 'Recipient Dude <recipient@example.com>',
        ]);
        $command = new MigrateRecipients;

        $command->handle();

        $this->assertDatabaseHas('sent_emails', [
            'id' => $tracker->id,
            'sender_name' => 'Sender Dude',
            'sender_email' => 'sender@example.com',
            'recipient_name' => 'Recipient Dude',
            'recipient_email' => 'recipient@example.com',
        ]);
    }

    /**
     * @test
     */
    public function it_converts_existing_recipient_values_with_no_name()
    {
        Schema::connection((new SentEmail())->getConnectionName())->table('sent_emails', function (Blueprint $table) {
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
        });
        SentEmail::unguard();
        $tracker = SentEmail::create([
            'hash' => 'email-hash',
            'sender' => ' <sender@example.com>',
            'recipient' => ' <recipient@example.com>',
        ]);
        $command = new MigrateRecipients;

        $command->handle();

        $this->assertDatabaseHas('sent_emails', [
            'id' => $tracker->id,
            'sender_name' => '',
            'sender_email' => 'sender@example.com',
            'recipient_name' => '',
            'recipient_email' => 'recipient@example.com',
        ]);
    }
}
