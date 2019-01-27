<?php

namespace App\Http\Controllers;

use App\Export\CaseExport;
use App\Http\Filters\CaseFilter;
use App\Http\Mapping\MappingCase;
use App\Http\Resources\CaseDataResource;
use App\Http\Sortable\SortableCase;

class ResponseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $caseType
     * @param CaseFilter $caseFilter
     * @param SortableCase $sortableCase
     * @param MappingCase $mappingCase
     * @return CaseDataResource
     */
    public function index($caseType, CaseFilter $caseFilter, SortableCase $sortableCase, MappingCase $mappingCase)
    {

        abort_unless(auth()->user()->hasPermissionTo("cases.{$caseType}"), 403);

        $query = get_case_type_model($caseType)->query();

        $query->filter($caseFilter);

        $query->sort($sortableCase);

        $query->mapping($mappingCase);

        if(empty(request('sorting.column'))){
            $query->orderBy('opened_at', 'desc');
        }

        $results = $this->handlePagination($query);

        $collection = case_resource_collection($caseType, $results, $caseType);

        if (request('export')) {
            return export(CaseExport::class, $caseType . '_' . now()->format('Y:m:d'), $collection);
        }

        return $collection;
    }

    public function handlePagination($query)
    {
        if (request()->has('paginate') and request()->get('paginate') === "false") {
            $results = $query->get();
        } else {
            $results = $query->paginate($this->perPage());
        }
        return $results;
    }

    protected function perPage()
    {
        $perPage = request("perPage");
        $perPagesLimit = [15, 30, 50];

        if (!in_array($perPage, $perPagesLimit)) {
            $perPage = 15;
        }

        return $perPage;
    }
}
