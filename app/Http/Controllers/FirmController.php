<?php

namespace App\Http\Controllers;

use App\Export\CaseExport;
use App\Http\Filters\CaseFilter;
use App\Http\Requests\FirmRequest;
use App\Http\Sortable\SortableCase;
use App\Models\Firm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirmController extends Controller
{
    /**
     * Display a listing of all Job Seekers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FirmRequest $request)
    {
        return view('firm.index');
    }

    /**
     * Display Job Seeker details page.
     *
     * @param FirmRequest $request
     * @param Firm $firm
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(FirmRequest $request, Firm $firm)
    {
        return view('firm.show', compact('firm'));
    }

    public function matches(Firm $firm, CaseFilter $filter, SortableCase $sortableCase)
    {
        $query = $firm->hiredMatches();

        $query->filter($filter);

        $query->sort($sortableCase);

        $results = $query->paginate();

        $caseType = 'job-seeker';

        $collection = case_resource_collection($caseType, $results, $caseType);

        if (request('export')) {
            return export(CaseExport::class, $caseType . '_' . now()->format('Y:m:d'), $collection);
        }

        return $collection;
    }
}
