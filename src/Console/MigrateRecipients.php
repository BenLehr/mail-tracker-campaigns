<?php

namespace benlehr\MailTracker\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use benlehr\MailTracker\Model\SentEmail;

class MigrateRecipients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail-tracker:migrate-recipients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert recipient/sender columns to 5.x format';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $bar = optional($this->output)->createProgressBar(SentEmail::count());
        optional($bar)->start();
        DB::connection((new SentEmail)->getConnectionName())->table('sent_emails')->orderBy('id')->chunk(100, function ($emails) use ($bar) {
            $emails->each(function ($email) use ($bar) {
                if ($email->recipient_email == null) {
                    $this->migrateEmail($email);
                }
                optional($bar)->advance();
            });
        });
        optional($bar)->finish();
    }

    protected function migrateEmail($email)
    {
        $sender_info = preg_match("/^([^<]*) <(.*)>$/", $email->sender, $matches);
        if ($sender_info) {
            $sender_name = $matches[1];
            $sender_email = $matches[2];
        }
        $recipient_info = preg_match("/^([^<]*) <(.*)>$/", $email->recipient, $matches);
        if ($recipient_info) {
            $recipient_name = $matches[1];
            $recipient_email = $matches[2];
        }
        SentEmail::where('id', $email->id)
            ->update([
                'sender_name' => trim($sender_name),
                'sender_email' => trim($sender_email),
                'recipient_name' => trim($recipient_name),
                'recipient_email' => trim($recipient_email),
            ]);
    }
}
