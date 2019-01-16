<?php namespace App\Http\Cards;


use App\Http\Controllers\HasAuth;
use App\Models\Firm;
use App\Models\Followup;
use App\Models\JobOpening;
use App\Models\JobSeeker;

class Cards
{
    use HasAuth;

    public function get()
    {
        return [
            JobSeekerCard::make(trans('irc.total_monthly_intakes'))
                ->model(JobSeeker::class)
                ->authorize($this->isEso())
                ->count(),
            FollowUpCard::make(trans('irc.total_pending_followups'))
                ->for(JobSeeker::class)
                ->authorize($this->isEso())
                ->count(),


            FollowUpCard::make(trans('irc.total_pending_followups'))
                ->for(Firm::class)
                ->authorize($this->isEsso())
                ->count(),
            ValueCard::make(trans('irc.total_job_openings'))
                ->model(JobOpening::class)
                ->authorize($this->isEsso())
                ->count(),
            FirmCard::make(trans('irc.total_firms_per_user'))
                ->model(Firm::class)
                ->authorize($this->isEsso())
                ->count()
        ];

    }
}
