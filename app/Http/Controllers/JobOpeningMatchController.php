<?php

namespace App\Http\Controllers;

use App\Export\CaseExport;
use App\Http\Filters\CaseFilter;
use App\Http\Filters\MatchStatusFilter;
use App\Http\Resources\CaseResources\JobOpeningMatchResourceCollection;
use App\Http\Sortable\SortableCase;
use App\Http\Sortable\SortableMatch;
use App\Models\JobOpening;
use App\Models\Match;

class JobOpeningMatchController extends Controller
{

    public function index(JobOpening $jobOpening)
    {
        abort_unless(auth()->user()->hasPermissionTo("cases.match"), 403);

        $jobOpening->load('firm');

        return view('job-opening.match', compact('jobOpening'));
    }

    public function matches(JobOpening $jobOpening, CaseFilter $filter, MatchStatusFilter $matchStatusFilter, SortableCase $sortableCase, SortableMatch $sortableMatch)
    {
        abort_unless(auth()->user()->hasPermissionTo("cases.match"), 403);

        $caseType = 'job-seeker';

        $query = get_case_type_model($caseType)->query();

        $query->withCandidateInJobOpening($jobOpening->id);

        $query->filter($filter);

        $query->filter($matchStatusFilter);

        $query->sort($sortableCase);

        $query->sort($sortableMatch);

        $results = $query->paginate($this->perPage());

        $collection = new JobOpeningMatchResourceCollection($results, $caseType);

        $collection->additional([
            'matches' => $jobOpening->matchesFromPivot()->pluck('job_seeker_id')
        ]);

        return $collection;
    }


    public function saved(JobOpening $jobOpening)
    {
        if(request()->wantsJson()){
            return $this->savedMatches($jobOpening, request());
        }

        return view('job-opening.saved', compact('jobOpening'));
    }

    public function savedList(JobOpening $jobOpening, CaseFilter $filter, SortableCase $sortableCase, SortableMatch $sortableMatch, MatchStatusFilter $matchStatusFilter)
    {
        $caseType = 'job-seeker';

        $query = $jobOpening->matches();

        $query->filter($filter);

        $query->filter($matchStatusFilter);

        $query->sort($sortableCase);

        $query->sort($sortableMatch);

        $results = $this->handlePagination($query);

        $collection = new JobOpeningMatchResourceCollection($results, $caseType);

        if (request('export')) {
            return export(CaseExport::class, $caseType . '_' . now()->format('Y:m:d'), $collection);
        }


        return $collection;
    }


    public function store(JobOpening $jobOpening)
    {
        abort_unless(auth()->user()->hasPermissionTo("cases.match.save"), 403);

        request()->validate([
            'matches' => 'present|array',
            'matches.*' => 'numeric'
        ]);

        $matches = request('matches');

        Match::where('job_opening_id', $jobOpening->id)->whereNotIn('job_seeker_id', $matches)->delete();


        foreach($matches as $match){
            $data = [
                'job_seeker_id' => $match,
                'firm_id' => $jobOpening->firm_id
            ];

            $attributes = [
                'job_seeker_id' => $match,
                'job_opening_id' => $jobOpening->id
            ];

            Match::updateOrCreate($attributes, $data);
        }
    }

    protected function perPage()
    {
        $perPage = request("perPage");
        $perPagesLimit = [15, 30, 50];

        if (!in_array($perPage, $perPagesLimit)) {
            $perPage = 50;
        }

        return intval($perPage);
    }


    public function handlePagination($query)
    {
        if (request('export')) {
            $results = $query->get();
        } else {
            $results = $query->paginate($this->perPage());
        }
        return $results;
    }



}
