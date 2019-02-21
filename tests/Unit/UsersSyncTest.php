<?php

namespace Tests\Unit;

use App\Models\User;
use App\Sync\UsersSync;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_create_new_users()
    {
        $this->mockUsersRequest();

        app(UsersSync::class)->make();

        $this->assertCount(
            20, User::all()
        );
    }


    public function test_it_will_update_exists_users()
    {
        factory(User::class)->create([
            'commcare_id' => '397406ffcbac576042e0f3e6f02212cc'
        ]);

        factory(User::class)->create([
            'email' => 'muntasir.shami@hotmail.com'
        ]);

        $this->mockUsersRequest();

        app(UsersSync::class)->make();

        $this->assertCount(
            20, User::all()
        );

        $this->assertNotEmpty(
            User::where('email','muntasir.shami@hotmail.com')->first()->commcare_id
        );
    }


    public function test_it_update_email_if_changed()
    {
        factory(User::class)->create([
            'commcare_id' => '397406ffcbac576042e0f3e6f02212cc'
        ]);

        factory(User::class)->create([
            'email' => 'muntasir.shami@hotmail.com'
        ]);

        $this->mockUsersRequest([
            'meta' => [
                'next' => null
            ],
            'objects' => [
                [
                    'id' => '397406ffcbac576042e0f3e6f02212cc',
                    'email' => 's@asd.com'
                ],
                [
                    'id' => '397406ffcbac576042e0f3e6f02212cc',
                    'email' => 'muntasir.shami@hotmail.com'
                ]
            ]
        ]);

        app(UsersSync::class)->make();

        $this->assertCount(
            2, User::all()
        );

        $this->mockUsersRequest([
            'meta' => [
                'next' => null
            ],
            'objects' => [
                [
                    'id' => '397406ffcbac576042e0f3e6f02212cc',
                    'email' => 'solaiman@souktel.com'
                ],
                [
                    'id' => '397406ffcbac576042e0f3e6f02212cc',
                    'email' => 'muntasir.shami@hotmail.com'
                ]
            ]
        ]);

        app(UsersSync::class)->make();

        $this->assertCount(
            2, User::all()
        );

        $this->assertEquals(
            'solaiman@souktel.com',
            User::where('commcare_id','397406ffcbac576042e0f3e6f02212cc')->first()->email
        );

    }

    public function test_it_will_not_update_email_if_already_exists_for_another_user()
    {
        factory(User::class)->create([
            'commcare_id' => 1,
            'email' => 'user1@souktel.com'
        ]);

        factory(User::class)->create([
            'email' => 'user2@souktel.com',
            'commcare_id' => 2
        ]);

        $this->syncUsers([
            'meta' => [
                'next' => null
            ],
            'objects' => [
                [
                    'id' => 1,
                    'email' => 'user3@souktel.com'
                ],
                [
                    'id' => 2,
                    'email' => 'user2@souktel.com'
                ]
            ]
        ]);

        $this->assertCount(
            2, User::all()
        );

        $this->syncUsers([
            'meta' => [
                'next' => null
            ],
            'objects' => [
                [
                    'id' => 1,
                    'email' => 'user2@souktel.com'
                ],
                [
                    'id' => 2,
                    'email' => 'user2@souktel.com'
                ]
            ]
        ]);

        $this->assertCount(
            2, User::all()
        );

        $this->assertEquals(
            'user3@souktel.com',
            User::where('commcare_id',1)->first()->email
        );

        $this->assertEquals(
            'user2@souktel.com',
            User::where('commcare_id',2)->first()->email
        );

    }

}
