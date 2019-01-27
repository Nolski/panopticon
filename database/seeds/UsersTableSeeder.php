<?php

use Illuminate\Database\Seeder;
use \App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUser([
            'name' => 'ML User',
            'email' => 'ml@tashbeek.org',
            'password' => bcrypt('irc123!')
        ]);

        $this->createUser([
            'name' => 'Admin User',
            'email' => 'admin-tashbeek@souktel.com',
            'password' => bcrypt('tashbeek123!')
        ]);
    }

    protected function createUser(array $user)
    {

        $tashbeekUser = User::where('email', $user['email'])->first();

        if ($tashbeekUser) {
            return;
        }

        if (isset($user['password']) and $user['password']) {
            $user['password'] = bcrypt($user['password']);
        }

        User::query()->insert($user);
    }
}
