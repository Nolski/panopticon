<?php

namespace App\Models;

use App\Models\MediaTrait\MediaFunctionality;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements HasMedia
{
    use Notifiable,
        SoftDeletes,
        MediaFunctionality,
        Notifiable,
        HasRoles,
        HasFilter,
        Mapping,
        Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'name', 'email', 'password', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];


    protected $appends = ['profile_picture'];

    public static function boot()
    {
        parent::boot();

        parent::creating(function ($model) {
            $model->api_token = \Illuminate\Support\Str::random(50);
        });
    }

    public function getProfilePictureAttribute()
    {
        $media = $this->getAllAttachmentsAttribute(true, 'profile_picture')->first();

        $url = $media ? url($media->getUrl()) : '/profile_picture.svg';

        return $url;
    }

}
