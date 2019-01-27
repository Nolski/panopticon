<?php

namespace App\Http\Resources;

use App\Repositories\PropertyMetaData\PropertyMetaDataRepositoryInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CaseDataResource extends ResourceCollection
{
    protected $caseType;

    public $collects = CaseResource::class;

    /**
     * DataResource constructor.
     * @param $resource
     * @param $caseType
     */
    public function __construct($resource, $caseType)
    {
        parent::__construct($resource);
        $this->caseType = $caseType;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        $properties = app(PropertyMetaDataRepositoryInterface::class)->typeIs($this->caseType)->keyBy('column_name');

        $headers = [];
        $columns = [];
        $filters = [];

        $selectedColumns = $this->getSelectedColumns($request, $properties);

        foreach ($properties as $property) {

            $label = trans_commcare(array_get($property->attributes, 'translations', []), $property->column_name);

            if (in_array($property->column_name, $selectedColumns)) {
                $headers[] = [
                    'label' => $label,
                    'name' => $property->column_name,
                ];
            }

            $columns[] = [
                'label' => $label,
                'name' => $property->column_name,
            ];

            $filters[] = [
                'name' => $property->column_name,
                'label' => $label,
                'type' => isset($property->attributes['type']) ? strtolower($property->attributes['type'])  : 'text',
                'options' => PropertyOptionsResource::collection(collect($property->attributes['options'] ?? [])),
            ];
        }

        $headers[0]['clickable_from'] = 'details_url';

        $permissions = [
            'notes' => $this->canAddNotes($request),
            'can_see' => $this->hasPermissionOn($request,'cases.' . $this->caseType)
        ];

        if($this->caseType === 'job-opening'){
            $permissions['can_see'] =    $this->hasPermissionOn($request,'cases.matches');
        }

        return [
            'headers' => $headers,
            'filters' => $filters,
            'columns' => $columns,
            'sorting' => $this->getSorting($request, $properties),
            'permissions' => $permissions
        ];
    }

    protected function getSorting($request, $properties)
    {
        $model = get_case_type_model($this->caseType);

        $sorting = $request->input('sorting', []);

        if (!is_array($sorting) || (!in_array(array_get($sorting, 'column'), withCount($model)) and !$properties->has($request->input('sorting.column')))) {
            $sorting = [
                'column' => null,
                'type' => array_get($sorting, 'type')
            ];
        }



        return [
            'column' => array_get($sorting, 'column'),
            'type' => array_get($sorting, 'type') === 'asc' ? 'asc' : 'desc',
        ];
    }

    protected function getSelectedColumns($request, $properties)
    {
        $columns = [];
        if ($request->has('columns')) {
            if (is_array($request->input('columns'))) {
                $columns = array_flatten($request->input('columns'));
            } else {
                $columns = explode(',', $request->input('columns'));
            }
        }

        if (!is_array($columns)) {
            $columns = [];
        }

        $columns = array_filter($columns, function ($column) use ($properties) {
            return $properties->has($column);
        });

        if (!count($columns)) {
            return $properties->pluck('column_name')->take(15)->toArray();
        }

        return $columns;
    }

    protected function canAddNotes($request)
    {
        try{
            return $request->user()->hasPermissionTo("notes.{$this->caseType}");
        }catch (\Throwable $e){
            return false;
        }
    }
    protected function hasPermissionOn($request,$permission)
    {
        try{
            return $request->user()->hasPermissionTo($permission);
        }catch (\Throwable $e){
            return false;
        }
    }

}
