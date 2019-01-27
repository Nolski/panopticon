<?php

namespace App\Http\Controllers;

use App\Export\FollowupsExport;
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

        $query->where('user_id', auth()->id());

        if (request('export')) {
            $results = $query->get();
        }else{
            $results = $query->paginate();
        }

        $collection = FollowupResource::collection($results);

        if (request('export')) {
            return export(FollowupsExport::class, 'followups' . '_' . now()->format('Y:m:d'), $collection);
        }

        return $collection;
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

        $query->where('user_id', auth()->id());

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
