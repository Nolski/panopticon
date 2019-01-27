<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\JobSeeker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseNotesIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_case_notes_requires_auth()
    {
        $this->syncStructure('job-seeker');

        $jobSeeker = factory(JobSeeker::class)->create();

        $this->json('get', route('case-notes.index', ['job-seeker', $jobSeeker->id]))
            ->assertStatus(401);
    }

    public function test_index_case_404_on_invalid_case()
    {
        $this->syncStructure('job-seeker');

        $this->loginApi();

        $this->json('get', route('case-notes.index', ['invalid-case', 1]))
            ->assertStatus(404);
    }

    public function test_index_case_404_on_invalid_case_id()
    {
        $this->syncStructure('job-seeker');

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), [
            'notes.job-seeker'
        ]);

        $this->json('get', route('case-notes.index', ['job-seeker', 1]))
            ->assertStatus(404);
    }

    public function test_it_list_notes_of_case()
    {
        $this->withoutExceptionHandling();

        $this->syncStructure('job-seeker');

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), [
            'notes.job-seeker'
        ]);

        $jobSeeker = factory(JobSeeker::class)->create();

        $perPage = 10;
        $notesCount = 50;

        $this->createManyNotes($jobSeeker, $notesCount);

        $this->json('get', route('case-notes.index', ['job-seeker', 1]))
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'note',
                        'user',
                        'type'
                    ]
                ]
            ])
            ->assertJsonCount($perPage, 'data')
            ->assertJson([
                'meta' => [
                    'total' => $notesCount,
                    'last_page' => ceil($notesCount / $perPage),
                    'per_page' => $perPage
                ]
            ])
            ->assertStatus(200);
    }

    public function createManyNotes($case, $times = 10)
    {
        for ($i = 1; $i <= $times; $i++) {
            $case->addNote([
                'note' => $this->faker->sentence,
                'user_id' => auth()->id()
            ]);
        }
    }
}
