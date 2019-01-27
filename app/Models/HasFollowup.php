<?php namespace App\Models;

trait HasFollowup
{
    public function followups()
    {
        return $this->morphMany(Followup::class, 'followup');
    }

    public static function bootHasFollowup()
    {
        static::deleting(function ($model) {
            $model->followups()->delete();
        });
    }
}