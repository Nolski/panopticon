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

            // ESO
            JobSeekerCard::make(trans('irc.total_job_seekers_monthly_intakes'))
                ->model(JobSeeker::class)
                ->authorize($this->isEso())
                ->with('icon', 'icon-Users_2_x40_2xpng_2')
                ->count(),

            FollowUpCard::make(trans('irc.total_pending_followups'))
                ->authorize($this->isEso())
                ->with('icon', 'icon-Calendar_2_x40_2xpng_2')
                ->count(),

            // ESSO
            FollowUpCard::make(trans('irc.total_pending_followups'))
                ->authorize($this->isEsso())
                ->with('icon', 'icon-Calendar_2_x40_2xpng_2')
                ->count(),
            JobOpeningCard::make(trans('irc.total_job_openings'))
                ->authorize($this->isEsso())
                ->with('icon', 'icon-Suitcase_x40_2xpng_2')
                ->count(),
            FirmCard::make(trans('irc.total_firms_per_user'))
                ->model(Firm::class)
                ->authorize($this->isEsso())
                ->with('icon', 'icon-Storefront_x40_2xpng_2')
                ->count()
        ];

    }
}
