<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use benlehr\MailTracker\Model\SentEmail;
use Illuminate\Database\Migrations\Migration;

class AddFirstOpenAndFirstClickColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection((new SentEmail())->getConnectionName())->table('sent_emails', function (Blueprint $table) {
            $table->datetime('clicked_at')->nullable()->after('updated_at');
            $table->datetime('opened_at')->nullable()->after('updated_at');
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
            $table->dropColumn('opened_at');
            $table->dropColumn('clicked_at');
        });
    }
}
