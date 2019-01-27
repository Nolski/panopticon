<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['note', 'type', 'user_id', 'is_starred'];

    protected $casts = [
        'is_starred' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOnlyStarred($builder)
    {
        $builder->where('is_starred',1);
    }
}
