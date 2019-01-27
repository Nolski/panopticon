<?php namespace App\Http\Sortable;

use App\Models\PropertyMetaData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SortableCase implements SortableInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * SortableCase constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Builder $builder)
    {
        $model = $builder->getModel();

        $caseType = case_type($model);

        if (!$this->request->has('sorting.column')) {
            return;
        }

        $columnName = $this->request->input('sorting.column');

        if (!in_array($columnName, withCount($model)) and PropertyMetaData::typeIs($caseType)->columnIs($columnName)->count() === 0) {
            return;
        }

        $sortingType = $this->request->input('sorting.type');

        $sortingType = $sortingType === 'desc' ? 'desc' : 'asc';

        $builder->orderBy($columnName, $sortingType);
    }
}