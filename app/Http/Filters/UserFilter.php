<?php namespace App\Http\Filters;

use Illuminate\Http\Request;
use App\Models\PropertyMetaData;
use Illuminate\Database\Eloquent\Builder;

class UserFilter implements FilterInterface
{

    protected $validFilters = ['name', 'email'];
    /**
     * @var Request
     */
    protected $request;

    /**
     * CaseFilter constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply filter
     *
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $filters = $this->getFilters();
        foreach ($filters as $key => $value) {
            if(is_array($value)){
                $value = array_filter($value,function($value){
                    return $this->isValidValue($value);
                });
            }

            if (!$this->isValidValue($value)) {
                continue;
            }

            switch ($key){
                case 'role':
                    $builder->whereHas('roles',function($query) use($value){
                        if(!is_array($value)){
                            $value = [$value];
                        }
                        $query->whereIn('id', $value);
                    });
                    break;
                default:
                    $builder = $builder->where($key, 'LIKE', "%$value%");
                    break;
            }
        }
    }

    protected function getFilters()
    {
        $filters = $this->request->get('filters', []);

        if (!is_array($filters)) {
            $filters = [$filters];
        }

        $filters = array_filter($filters, function ($item) {
            return !is_null($item) and !in_array($item, $this->validFilters);
        });

        return $filters;
    }



    protected function isValidValue($value)
    {
        if(is_array($value)){
            return count($value) > 0;
        }

        return !is_null($value) && trim($value) !== "";
    }

}