<?php namespace App\Http\Cards;
use App\Models\JobOpening;


/**
 * Class JobSeekerCard
 * @package App\Http\Cards
 */
class JobOpeningCard extends Card
{
    protected $method = 'count'; // initial value

    protected $model = JobOpening::class;

    /**
     * @return ValueCard
     */
    public function apply()
    {
        $query = $this->getQuery();

        switch ($this->method) {
            case 'count':
            default:
                return $query->count();

        }
    }

    public function component()
    {
        return 'value-card';
    }


    /**
     * @param $model
     * @return $this
     */
    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function getQuery()
    {
        return app($this->model)->query();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        $this->method = 'count';
        return $this;
    }


}
