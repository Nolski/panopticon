<?php namespace App\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class MatchStatusFilter implements FilterInterface
{
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
        if(!$this->request->has('filters.match_status')){
            return ;
        }

        $value = $this->request->input('filters.match_status');

        if(!is_array($value)){
            $value = [$value];
        }

        $value = array_map('trim', $value);

        $value = array_filter($value);

        if(!count($value)){
            return;
        }

        $builder->where(function(Builder $query) use($value){
            $query->whereIn('matches.status', $value);

            if(in_array(\App\Models\Match::STATUS_NEW, $value)){
                $query->orWhereNull('matches.status');
            }
        });
    }
}