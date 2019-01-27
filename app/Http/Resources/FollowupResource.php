<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'followup_type' => case_type($this->followup_type),
            'followup_date' => $this->followup_date,
            'followup_period' => $this->followup_period,
            'type' => $this->type,
            'due_date' => $this->due_date,
            'followup' => [
                'id' => $this->followup->id,
                'name' => $this->getResourceDisplayName($this->followup),
                'background' => $this->getBasicInfo($this->followup),
            ]
        ];
    }

    protected function getResourceDisplayName($resource)
    {
        if(method_exists($resource,'displayName')){
            return $resource->displayName();
        }

        return $resource;
    }

    private function getBasicInfo($resource)
    {
        if(method_exists($resource,'basicInfo')){
            return array_filter($resource->basicInfo());
        }

        return '';
    }
}
