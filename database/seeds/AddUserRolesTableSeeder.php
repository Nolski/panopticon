<?php

use Illuminate\Database\Seeder;
use \App\Models\User;

class AddUserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = $this->createUser([
            'name' => 'System Administrator',
            'email' => 'administrator@tashbeek.org',
            'password' => 'irc123!'
        ]);

        $admin = $this->createUser([
            'name' => 'Project Match Admin',
            'email' => 'admin@tashbeek.org',
            'password' => 'irc123!'
        ]);

        $esso = $this->createUser([
            'name' => 'Employment Senior Service Officer',
            'email' => 'esso@tashbeek.org',
            'password' => 'irc123!'
        ]);

        $eso = $this->createUser([
            'name' => 'Employment Service Officer',
            'email' => 'eso@tashbeek.org',
            'password' => 'irc123!'
        ]);

        $administrator->assignRole('System Administrator');

        $admin->assignRole('Project Match Admin');

        $eso->assignRole('Employment Service Officer (ESO)');

        $esso->assignRole('Employment Senior Service Officer (ESSO)');

    }

    protected function createUser(array $user)
    {

        $tashbeekUser = User::where('email', $user['email'])->first();

        if ($tashbeekUser) {
            return null;
        }

        if (isset($user['password']) and $user['password']) {
            $user['password'] = bcrypt($user['password']);
        }

        return User::query()->create($user);
    }
}
