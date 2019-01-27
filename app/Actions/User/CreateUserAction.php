<?php namespace App\Actions\User;

use App\Models\Form;
use App\Models\User;
use App\Sync\Cases\AbstractCase;
use App\Sync\Cases\Firm;
use App\Sync\Cases\Match;
use App\Sync\Cases\JobSeeker;
use App\Sync\Cases\JobOpening;

class CreateUserAction
{
    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        if (isset($data['password']) and $data['password']) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = User::query()->create($data);

        $roles = $data['roles'] ?? [];

        $user->assignRole($roles);

        try {
            $user->saveAttachments('profile_picture', 'profile_picture');
        } catch (\Exception $exception) {

        }
        return $user;
    }

}

