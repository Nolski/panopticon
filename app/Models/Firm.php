<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class Firm extends Model implements SyncableInterface
{
    use MorphToForm,
        Routable,
        HasFilter,
        Sortable,
        HasFollowup,
        Notable,
        Mapping,
        HasActivity;

    public $withCount = ['openings'];

    protected $appends = [
        'details_url'
    ];

    protected $guarded = ['id'];

    public function openings()
    {
        return $this->hasMany(JobOpening::class);
    }

    public function scopeByCommCareId(Builder $builder, $commCareId)
    {
        $builder->where('commcare_id', $commCareId);
    }

    public function displayName()
    {
        return $this->firm_name;
    }

    public function basicInfo()
    {
        $data = [
            'gender' => $this->district,
            'city' => $this->city,
            'contact_mobile' => $this->contact_mobile,
            'followup_contact_mobile' => $this->followup_contact_mobile,
        ];
        $data = array_filter($data);

        return array_only(map_options($data,$this), array_keys($data));
    }


    public function matches()
    {
        return $this->belongsToMany(JobSeeker::class, 'matches');
    }


    public function hiredMatches()
    {
        return $this->matches()->where('matches.status', Match::STATUS_HIRED);
    }

}
