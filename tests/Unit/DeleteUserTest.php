<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_is_soft_deleted()
    {

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $this->delete('api/users/' . $user->id)
            ->assertStatus(200);


        $this->assertNull(User::find(1));
        $this->assertNotNull(User::withTrashed()->find(1));

    }


    private function createUser()
    {
        $user = factory(User::class)->create([
            'name' => 'Mohammed',
            'email' => 'sehweil@gmail.com',
            'password' => bcrypt('sehweil'),
        ]);

        return $user;
    }

}
