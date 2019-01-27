<?php namespace App\Http\Mapping;

use App\Models\JobOpening;
use App\Models\PropertyMetaData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MappingCase implements MappingInterface
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
        $model = get_class($builder->getModel());

        if (!$this->request->has('sorting.column')) {
            return;
        }

        $columnName = $this->request->input('sorting.column');

        $sortingType = $this->request->input('sorting.type');

        $sortingType = $sortingType === 'desc' ? 'desc' : 'asc';

        switch ($model) {
            case JobOpening::class:
                $this->jobOpeningMapping($builder, $columnName, $sortingType);
        }

    }


    /**
     * @param $builder
     * @param $columnName
     * @param $sortingType
     */
    protected function jobOpeningMapping($builder, $columnName, $sortingType)
    {
        switch ($columnName) {
            case 'firm_id':

                $builder->getQuery()->orders = null;

                $builder
                    ->join('firms', 'job_openings.firm_id', 'firms.id')
                    ->orderBy('firms.firm_name', $sortingType);

        }
    }
}