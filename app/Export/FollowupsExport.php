<?php


namespace App\Export;

use Maatwebsite\Excel\Facades\Excel;

class FollowupsExport extends Export
{


    /**
     * @return array|mixed
     */
    public function getHeaders()
    {
        return [
            trans('irc.id'),
            trans('irc.type'),
            trans('irc.name'),
            trans('irc.due_date'),
            trans('irc.background'),
        ];
    }


    /**
     * @return array|mixed
     */
    public function getData()
    {
        $followups = $this->data->toArray(request());
        $followups = array_map(function ($item) {
            return [
                'id' => array_get($item, 'followup.id'),
                'type' => array_get($item,'type'),
                'followup_name' => array_get($item,'followup.name'),
                'due_date' => array_get($item,'followup_date'),
                'background' => implode(', ', array_get($item,'followup.background',[])),
            ];

        }, $followups);

        return $followups;
    }


}
