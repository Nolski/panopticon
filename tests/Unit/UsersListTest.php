<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Faker\Generator as Faker;

class UsersListTest extends TestCase
{
    use RefreshDatabase;


    public function test_users_list()
    {


        $users = factory(User::class, 5)->create();

        $this->loginApi($users[0]);  // login with the one of the users. so the count will be 5 not 6.

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $response = $this->get('api/users')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'email',
                        'status',
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ]
            ]);
    }


}
