<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowupResource;
use App\Models\Followup;

class UpcomingFollowupsController extends Controller
{
    public function index()
    {
        request()->validate([
            'followup_date' => 'nullable|date_format:Y-m-d'
        ]);

        $query = Followup::query();

        if(request('followup_date')){
            $query->upcomingOnDate(request('followup_date'));
        }else{
            $query->upcoming();
        }

        $query->with('followup');

        $results = $query->paginate();

        return FollowupResource::collection($results);
    }


    public function counts()
    {
        request()->validate([
            'followup_date' => 'nullable|date_format:Y-m'
        ]);

        $query = Followup::query();

        $query->select(['followup_date', \DB::raw('count(id)  as followup_count')]);

        $query->groupBy('followup_date');

        $query->orderBy('followup_date','asc');

        $query->whereBetween('followup_date',$this->getStartAndEndOfMonth(request('followup_date')));

        return [
            'data' => $query->get()->map(function($item){
                $item['followup_count'] = intval($item['followup_count']);
                return $item;
            })
        ];

    }

    protected function getStartAndEndOfMonth($date)
    {
        if(!$date){
            $date = now();
        }else{
            $date = \Carbon\Carbon::createFromFormat('Y-m',$date);
        }

        return [
            $date->startOfMonth()->toDateString(),
            $date->endOfMonth()->toDateString()
        ];
    }
}