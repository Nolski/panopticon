<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

class JobOpening extends Model implements SyncableInterface
{
    use MorphToForm, HasFilter, Sortable, Notable, Mapping;

    protected $guarded = ['id'];

    protected $appends = ['matches_url','saved_matches_url'];

    public $withCount = ['matches', 'hiredMatches'];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function matches()
    {

        $builder = $this->belongsToMany(JobSeeker::class, 'matches')->withPivot(['status'])
            ->leftJoin('match_scores', function (JoinClause $clause) {

                $clause->on('job_seekers.id', '=', 'match_scores.job_seeker_id');

                $clause->where('match_scores.job_opening_id', $this->id);

            });


        $builder->addSelect('match_scores.score as score');
        $builder->addSelect('job_seekers.*');

        return $builder;
    }

    public function hiredMatches()
    {
        return $this->matches()->where('matches.status', Match::STATUS_HIRED);
    }

    public function matchesFromPivot()
    {
        return $this->hasMany(Match::class)->whereNotNull('job_seeker_id');
    }

    public function getMatchesUrlAttribute($value)
    {
        return route('job-openings.match', $this->id);
    }
    public function getSavedMatchesUrlAttribute($value)
    {
        return route('job-openings.saved', $this->id);
    }
}
