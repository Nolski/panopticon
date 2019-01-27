<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;


    public function test_name_is_required()
    {

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $response = $this->put('api/users/' . $user->id, []);

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['name']);

    }


    public function test_email_is_required()
    {
        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $response = $this->put('api/users/' . $user->id, []);

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['email']);
    }


    public function test_email_is_unique_in_update()
    {
        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $user1 = factory(User::class)->create(['email' => 'sehweil@gmail.com']);

        $user2 = factory(User::class)->create(['email' => 'ali@gmail.com']);

        $this->json('PUT', 'api/users/' . $user2->id, ['email' => 'sehweil@gmail.com'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

    }

    public function test_no_password_validation_if_empty()
    {

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $this->json('put', 'api/users/' . $user->id, ['name' => 's', 'email' => 'gg@gmail.com'])
            ->assertStatus(200)
            ->assertJsonMissingValidationErrors(['password']);

    }

    public function test_password_is_confirmed_if_not_empty()
    {
        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $data = [
            'name' => 's',
            'email' => 'gg@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'confirm'
        ];


        $this->json('put', 'api/users/' . $user->id, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }


    public function test_check_if_password_updated_is_encrypted()
    {

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $data = [
            'name' => 's',
            'email' => 'gg@gmail.com',
            'password' => 'password33333',
            'password_confirmation' => 'password33333'
        ];

        $this->json('put', 'api/users/' . $user->id, $data)
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertTrue(Hash::check($data['password'], $user->password));
    }


    public function test_password_not_changes_if_empty()
    {

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $data = [
            'name' => 's',
            'email' => 'gg@gmail.com',
        ];

        $this->json('put', 'api/users/' . $user->id, $data)
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertNotNull($user->password);
    }

    public function test_profile_picture_updated_successfully()
    {
        Storage::fake('upload');

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $profilePicture = UploadedFile::fake()->image('avatar.jpg');

        $diskName = 'upload';
        $collectionName = 'profile_picture';

        $user
            ->addMedia($profilePicture)
            ->toMediaCollection($collectionName, $diskName);

        Storage::disk('upload')->assertExists('/1/avatar.jpg');

        $data = [
            'name' => 'Mohammed',
            'email' => 'sehweil@gmail.com',
            'profile_picture' => UploadedFile::fake()->image('avatar-2.jpg')
        ];


        $this->json('PUT', 'api/users/' . $user->id, $data);

        $user = $user->fresh();

        Storage::disk('upload')->assertExists('/2/avatar-2.jpg');

    }


    public function test_profile_picture_if_empty()
    {
        Storage::fake('upload');

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $data = [
            'name' => 'Mohammed',
            'email' => 'sehweil@gmail.com',
            'password' => 'sehweil',
            'password_confirmation' => 'sehweil',
            'profile_picture' => null
        ];

        $this->json('PUT', 'api/users/' . $user->id, $data);

        $user = $user->fresh();

        $this->assertEquals('/profile_picture.svg', $user->profile_picture);
    }


    public function test_update_user()
    {
        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $data = [
            'name' => 'Hassan',
            'email' => 'hassan@gmail.com',
            'password' => 'hassan123123',
            'password_confirmation' => 'hassan123123',
        ];

        $response = $this->json('PUT', 'api/users/' . $user->id, $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => true,
            ]);


        $this->assertCount(1, User::all());
        $this->assertDatabaseHas('users', array_except($data, ['password_confirmation', 'password']));
    }


    public function test_user_roles()
    {

        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $user->assignRole([$role1->id,$role2->id]);

        $this->assertCount(3, $user->roles);

        $data = [
            'name' => 'Hassan',
            'email' => 'hassan@gmail.com',
            'password' => 'hassan123123',
            'password_confirmation' => 'hassan123123',
            'roles' => [1],
        ];

        $response = $this->json('PUT', 'api/users/' . $user->id, $data);

        $user = $user->fresh();

        $this->assertCount(1, $user->roles);

    }


    public function test_remove_all_roles_from_user()
    {

        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user = $this->createUser();

        $this->loginApi($user);

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        $user->assignRole([
            $role1->id,
            $role2->id
        ]);

        $this->assertCount(3, $user->roles);

        $data = [
            'name' => 'Hassan',
            'email' => 'hassan@gmail.com',
            'password' => 'hassan123123',
            'password_confirmation' => 'hassan123123',
        ];

        $response = $this->json('PUT', 'api/users/' . $user->id, $data);

        $user = $user->fresh();

        $this->assertCount(0, $user->roles);

    }

    private function createUser()
    {
        $user = factory(User::class)->create([
            'name' => 'Mohammed',
            'email' => 'sehweil@gmail.com',
            'password' => bcrypt('hassan123123'),
        ]);

        return $user;
    }

}