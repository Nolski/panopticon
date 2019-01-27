<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecentActivityResource extends JsonResource
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
            'created_at' => $this->created_at->toDateTimeString(),
            'created_at_text' => $this->created_at->format('l d F'),
            'entity_type' => case_type($this->entity_type),
            'title' => $this->title,
            'entity' => $this->whenLoaded('entity', function(){
                return [
                    'id' => $this->entity->id,
                    'name' => $this->getResourceDisplayName($this->entity),
                    'details_url' => $this->entity->details_url,
                ];
            }),
            'icon' => 'icon-Calendar_2_x40_2xpng_2'
        ];
    }

    protected function getResourceDisplayName($resource)
    {
        if(method_exists($resource,'displayName')){
            return $resource->displayName();
        }

        return $resource;
    }
}
