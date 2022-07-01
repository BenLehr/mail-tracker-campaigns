<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use benlehr\MailTracker\Model\SentEmail;
use Illuminate\Database\Migrations\Migration;

class AddMessageIdIndexToSentEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection((new SentEmail())->getConnectionName())->table('sent_emails', function (Blueprint $table) {
            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection((new SentEmail())->getConnectionName())->table('sent_emails', function (Blueprint $table) {
            $table->dropIndex('sent_emails_message_id_index');
        });
    }
}
