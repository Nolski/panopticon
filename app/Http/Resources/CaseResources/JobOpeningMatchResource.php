<?php

namespace App\Http\Resources\CaseResources;

use App\Http\Resources\CaseResource;
use App\Models\Match;
use Carbon\Carbon;

class JobOpeningMatchResource extends CaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['match_status'] = $this->matchStatus();

        $data['score'] = $this->matchScore();

        return $data;
    }

    protected function matchStatus()
    {

        $status = null;

        if ($this->pivot) {
            $status = $this->pivot->status;
        } else if ($this->match_status) {
            $status = $this->match_status;
        }

        if (empty($status)) {
            $status = Match::STATUS_NEW;
        }

        $status = trans('irc.match_statuses.' . $status);

        return $status;
    }

    protected function matchScore()
    {
        return ((double)$this->score * 100) . '%';
    }


}
