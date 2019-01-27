<?php

namespace App\Http\Resources\CaseResources;

use App\Http\Resources\CaseResource;
use Carbon\Carbon;

class JobOpeningResource extends CaseResource
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

        $data['firm_id'] = $this->firm ? $this->firm->firm_name : null;

        try {
            $data['date_opened_text'] = Carbon::parse($this->date_opened)->format('M d D');
        } catch (\Throwable $e) {
            $data['date_opened_text'] = null;
        }

        $data['matches_number'] = $this->matches_count;
        $data['hired_matches_count'] = $this->hired_matches_count;

        return $data;
    }
}
