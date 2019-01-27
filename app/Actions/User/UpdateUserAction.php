<?php namespace App\Actions\User;

use App\Models\Form;
use App\Models\User;
use App\Sync\Cases\AbstractCase;
use App\Sync\Cases\Firm;
use App\Sync\Cases\Match;
use App\Sync\Cases\JobSeeker;
use App\Sync\Cases\JobOpening;

class UpdateUserAction
{
    /**
     * @param User $user
     * @param $data
     * @return User|int
     */
    public function update(User $user, $data)
    {
        if (isset($data['password']) and $data['password']) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        $roles = $data['roles'] ?? [];
        $user->syncRoles($roles);

        $collectionName = 'profile_picture';
        try {
            if (request()->hasFile('profile_picture')) {
                $user->deleteFile($user->id, User::class, $collectionName);
                $user->saveAttachments('profile_picture', $collectionName);
            }

        } catch (\Exception $exception) {

        }
        return $user;
    }

}

