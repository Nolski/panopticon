<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class MatchScore extends Model
{

    protected $fillable = ['job_opening_id', 'job_seeker_id', 'score'];

}
