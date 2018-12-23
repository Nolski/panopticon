<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model implements SyncableInterface
{
    use MorphToForm, Routable, HasFilter, Sortable;

    protected $appends = [
      'details_url'
    ];

    protected $guarded = ['id'];
}