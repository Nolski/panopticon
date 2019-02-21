<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class JobSeeker extends Model implements SyncableInterface
{
    use Notable,
        Sortable,
        Routable,
        HasFilter,
        MorphToForm,
        HasFollowup,
        Mapping,
        HasActivity;

    protected $appends = [
        'details_url'
    ];

    protected $guarded = ['id'];


    public function scopeByCommCareId(Builder $builder, $commCareId)
    {
        $builder->where('commcare_id', $commCareId);
    }

    public function scopeCurrentMonth(Builder $builder)
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $builder->whereBetween('opened_at', [$startOfMonth, $endOfMonth]);
    }

    public function displayName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function matches()
    {
        return $this->belongsToMany(JobOpening::class, 'matches')->withPivot(['status as match_status']);
    }

    public function matchedMatches()
    {
        return $this->matches()->where('matches.status', Match::STATUS_MATCHED);
    }

    public function candidateMatches()
    {
        return $this->matches()->where('matches.status', Match::STATUS_CANDIDATE);
    }

    public function hiredMatches()
    {
        return $this->matches()->where('matches.status', Match::STATUS_HIRED);
    }

    public function scopeWithCandidateInJobOpening(Builder $builder, $jobOpeningId)
    {
        $builder
            ->leftJoin('matches', function (JoinClause $clause) use ($jobOpeningId) {

                $clause->on('job_seekers.id', '=', 'matches.job_seeker_id');

                $clause->where('matches.job_opening_id', $jobOpeningId);

            })
            ->leftJoin('match_scores', function (JoinClause $clause) use ($jobOpeningId) {

                $clause->on('job_seekers.id', '=', 'match_scores.job_seeker_id');

                $clause->where('match_scores.job_opening_id', $jobOpeningId);

            });


        if (!$builder->getQuery()->columns || !count($builder->getQuery()->columns)) {
            $builder->select($this->getTable() . '.*');
        }

        $builder->addSelect('matches.status as match_status');

        $builder->addSelect('match_scores.score as score');
    }

    public function basicInfo()
    {
        $data = [
            'age' => $this->age,
            'gender' => $this->gender,
            'city' => $this->city,
            'mobile_num' => $this->mobile_num
        ];
        return array_only(map_options($data,$this), array_keys($data));
    }

}