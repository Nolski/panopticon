<?php namespace App\Actions\User;

use App\Http\Filters\UserFilter;
use App\Http\Sortable\SortableUser;
use App\Models\User;

class UsersList
{
    public function get($pageLimit = 15)
    {
        $query = User::query();

        $query->filter(app(UserFilter::class));

        $query->sort(app(SortableUser::class));

        return $query->paginate($pageLimit);
    }
}

