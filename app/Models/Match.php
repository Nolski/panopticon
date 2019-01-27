<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model implements SyncableInterface
{
    use MorphToForm, HasFilter, Sortable, Mapping;

    protected $guarded = ['id'];

    const STATUS_NEW = 'new';

    const STATUS_CANDIDATE = 'candidate';

    const STATUS_MATCHED = 'matched';

    const STATUS_HIRED = 'hired';

    public function jobSeeker()
    {
        return $this->belongsTo(JobSeeker::class);
    }

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }
}