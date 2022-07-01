<?php


namespace benlehr\MailTracker;

use benlehr\MailTracker\Model\EmailCampaign;
use Carbon\Carbon;

class MailCampaignHelper
{

    public function createCampaign($name)
    {
        $campaign = new EmailCampaign();
        $campaign->name = $name . Carbon::now()->format('Y-m-d');
        $campaign->date = Carbon::now();
        $campaign->save();

        return $campaign->id;
    }

}
