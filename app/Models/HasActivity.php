<?php namespace App\Models;

trait HasActivity
{
    public function recentActivities()
    {
        return $this->morphMany(RecentActivity::class, 'entity');
    }


    public static function bootHasActivity()
    {
        static::deleting(function ($model) {
            $model->recentActivities()->delete();
        });
    }
}