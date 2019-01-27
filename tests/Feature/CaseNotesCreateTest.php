<?php

namespace Tests\Feature;

use App\Models\JobSeeker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaseNotesCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_a_note_requires_auth()
    {
        $this->syncStructure('job-seeker');

        $jobSeeker = factory(JobSeeker::class)->create();

        $this->json('post',route('case-notes.create', ['job-seeker', $jobSeeker->id]),['note' => '12'])
            ->assertStatus(401);

        $this->assertCount(0, $jobSeeker->notes);
    }

    public function test_it_return_404_on_invalid_case_type()
    {
        $this->loginApi();

        $this->json('post',route('case-notes.create', ['invalid-case-type', 1]),['note' => '12'])
            ->assertNotFound();
    }

    public function test_it_requires_a_note()
    {
        $this->syncStructure('job-seeker');

        $this->loginApi();

        $jobSeeker = factory(JobSeeker::class)->create();

        $this->json('post',route('case-notes.create', ['job-seeker', $jobSeeker->id]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('note');
    }

    public function test_note_should_be_a_string_only()
    {
        $this->syncStructure('job-seeker');

        $this->loginApi();

        $jobSeeker = factory(JobSeeker::class)->create();

        $this->json('post',route('case-notes.create', ['job-seeker', $jobSeeker->id]),['note' => []])
            ->assertStatus(422)
            ->assertJsonValidationErrors('note');
    }

    public function test_it_return_404_on_invalid_case_id()
    {
        $this->syncStructure('job-seeker');

        $this->loginApi();

        $this->json('post', route('case-notes.create', ['job-seeker', 1]),['note' => '1'])
            ->assertNotFound();
    }

    public function test_it_create_a_note_for_job_seeker()
    {
        $this->createCase('job-seeker');
    }

    public function test_it_create_a_note_for_firm()
    {
        $this->withoutExceptionHandling();


        $this->syncStructure('job-opening');

        $this->createCase('firm');
    }

    public function test_it_create_a_note_for_job_opening()
    {
        $this->withoutExceptionHandling();

        $this->syncStructure('job-seeker');
        $this->syncStructure('match');
        $this->createCase('job-opening');
    }

    public function createCase($caseType)
    {
        $this->syncStructure($caseType);

        $this->loginApi();

        $record = factory(get_class(get_case_type_model($caseType)))->create();

        $this->json('post',route('case-notes.create', [$caseType, $record->id]),['note' => '1'])
            ->assertStatus(201);

        $this->assertCount(1, $record->notes);
    }
}
