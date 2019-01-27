<?php namespace App\Http\Resources\CaseResources;

use App\Http\Resources\CaseResource;

class FirmResource extends CaseResource
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

        $data['city_key'] = $this->city;

        $data['openings_count'] = $this->openings()->count();

        return $data;
    }
}
