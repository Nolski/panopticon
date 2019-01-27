<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class ChangeStatusUserTest extends TestCase
{
    use RefreshDatabase;


    public function test_activate_user()
    {

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $user = $this->createUser('deactivated');

        $this->post('api/users/' . $user->id . '/activate')
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertEquals('activated', $user->status);

    }


    public function test_deactivate_user()
    {

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $user = $this->createUser();

        $this->post('api/users/' . $user->id . '/deactivate')
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertEquals('deactivated', $user->status);

    }


    private function createUser($status = 'activated')
    {
        $user = factory(User::class)->create([
            'name' => 'Mohammed',
            'email' => 'sehweil@gmail.com',
            'password' => bcrypt('sehweil'),
            'status' => $status,
        ]);

        return $user;
    }

}
