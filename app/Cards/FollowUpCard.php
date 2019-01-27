<?php namespace App\Http\Cards;

use App\Models\Followup;


/**
 * Class FollowUpCard
 * @package App\Http\Cards
 */
class FollowUpCard extends Card
{
    protected $method = 'count'; // initial value

    protected $model;

    protected $for;

    /**
     * @return ValueCard
     */
    public function apply()
    {
        $query = $this->getQuery();

        $query->currentUser();

        if($this->for){
            $query->ofType($this->for);
        }

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
     * @param $for
     * @return $this
     */
    public function for($for)
    {
        $this->for = $for;
        return $this;
    }

    public function getQuery()
    {
        return app(Followup::class)->query();
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
