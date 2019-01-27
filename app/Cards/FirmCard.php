<?php namespace App\Http\Cards;

use App\Models\Firm;

/**
 * Class FirmCard
 * @package App\Http\Cards
 */
class FirmCard extends Card
{
    protected $method = 'count'; // initial value

    protected $model;

    /**
     * @return ValueCard
     */
    public function apply()
    {
        $query = $this->getQuery();

        $query->where('user_id', auth()->user()->id);

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
