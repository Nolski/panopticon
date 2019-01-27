<?php

namespace Tests\Feature;

use App\Models\JobOpening;
use App\Models\JobSeeker;
use App\Models\Match;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaveMatchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_matches_requires_auth()
    {
        $this->syncStructure('job-opening');

        $jobOpening = factory(JobOpening::class)->create();

        $this->json('post', route('api.matches', $jobOpening->id), [
            'matches' => []
        ])->assertStatus(401);
    }

    public function test_save_matches_requires_match_save_permission()
    {
        $this->loginApi();

        $this->createPermissions('cases.match.save');

        $this->syncStructure('job-seeker');
        $this->syncStructure('match');
        $this->syncStructure('job-opening');

        $jobOpening = factory(JobOpening::class)->create();

        $this->json('post', route('api.matches', $jobOpening->id), [
            'matches' => []
        ])->assertStatus(403);
    }

    public function test_save_matches_requires_at_least_matches_param_required()
    {
        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');
        $this->syncStructure('job-seeker');
        $this->syncStructure('match');
        $this->syncStructure('job-opening');

        $jobOpening = factory(JobOpening::class)->create();

        $this->json('post', route('api.matches', $jobOpening->id))
            ->assertJsonValidationErrors('matches');
    }

    public function test_save_matches_requires_at_least_matches_param_as_array()
    {
        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');

        $this->syncStructure('job-seeker');
        $this->syncStructure('match');
        $this->syncStructure('job-opening');

        $jobOpening = factory(JobOpening::class)->create();

        $this->json('post', route('api.matches', $jobOpening->id),[
            'matches' => 'string'
        ])->assertJsonValidationErrors('matches');

    }

    public function test_save_matches_requires_matches_items_to_be_numeric()
    {
        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');

        $this->syncStructure('job-seeker');
        $this->syncStructure('match');
        $this->syncStructure('job-opening');


        $jobOpening = factory(JobOpening::class)->create();

        $this->json('post', route('api.matches', $jobOpening->id),[
            'matches' => [
                'oops',
                1
            ]
        ])
            ->assertJsonValidationErrors('matches.0')
            ->assertJsonMissingValidationErrors('matches.1');
    }

    public function test_it_save_matches()
    {
        $this->withoutExceptionHandling();

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');

        $this->syncStructure('job-seeker');

        $this->syncStructure('match');

        $this->syncStructure('job-opening');


        $jobOpening = factory(JobOpening::class)->create();

        $jobSeekers = factory(JobSeeker::class,5)->create();

        $match1 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(0)->id,
            'job_opening_id' => $jobOpening->id
        ]);

        $match2= factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(1)->id,
            'job_opening_id' => $jobOpening->id
        ]);

        $match3 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(2)->id,
            'job_opening_id' => $jobOpening->id
        ]);
        $match4 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(3)->id,
            'job_opening_id' => $jobOpening->id
        ]);

        $match5 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(4)->id,
            'job_opening_id' => $jobOpening->id
        ]);

        $this->assertCount(5, JobSeeker::all());

        $this->assertCount(5, $jobOpening->matches);

        $this->json('post', route('api.matches', $jobOpening->id), [
            'matches' => [$match1->job_seeker_id,$match2->job_seeker_id,]
        ])->assertStatus(200);

        $this->assertCount(2, $jobOpening->fresh()->matches);
    }

    public function test_it_save_matches_and_unset_who_is_not_selected()
    {
        $this->withoutExceptionHandling();

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');

        $this->syncStructure('job-opening');

        $this->syncStructure('job-seeker');

        $this->syncStructure('match');

        $jobOpening = factory(JobOpening::class)->create();

        $jobSeekers = factory(JobSeeker::class,5)->create();

        $match1 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(0)->id,
            'job_opening_id' => $jobOpening->id
        ]);

        $match2= factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(1)->id,
            'job_opening_id' => $jobOpening->id
        ]);

        $match3 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(2)->id,
            'job_opening_id' => $jobOpening->id
        ]);
        $match4 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(3)->id,
            'job_opening_id' => $jobOpening->id,
            'is_candidate' => 1
        ]);

        $match5 = factory(Match::class)->create([
            'job_seeker_id' => $jobSeekers->get(4)->id,
            'job_opening_id' => $jobOpening->id,
            'is_candidate' => 1
        ]);


        $this->assertCount(5, JobSeeker::all());

        $this->assertCount(5, $jobOpening->matches);

        $this->json('post', route('api.matches', $jobOpening->id), [
            'matches' => [$match1->job_seeker_id,$match2->job_seeker_id,]
        ])->assertStatus(200);

        $this->assertCount(2, $jobOpening->fresh()->matches);
    }

    public function test_it_create_match_record()
    {
        $this->withoutExceptionHandling();

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');

        $this->syncStructure('job-opening');

        $this->syncStructure('job-seeker');

        $this->syncStructure('match');

        $jobOpening = factory(JobOpening::class)->create();

        $jobSeekers = factory(JobSeeker::class,5)->create();

        $this->assertCount(5, JobSeeker::all());

        $this->json('post', route('api.matches', $jobOpening->id), [
            'matches' => [
                $jobSeekers->get(0)->id,
                $jobSeekers->get(1)->id,
            ]
        ])->assertStatus(200);

        $this->assertCount(2, $jobOpening->matches()->get());
    }

    public function test_it_has_candidate_as_default_value()
    {
        $this->withoutExceptionHandling();

        $this->loginApi();

        $this->createUserRoleWithPermission(auth()->user(), 'cases.match.save');

        $this->syncStructure('job-opening');

        $this->syncStructure('job-seeker');

        $this->syncStructure('match');

        $jobOpening = factory(JobOpening::class)->create();

        $jobSeekers = factory(JobSeeker::class,5)->create();

        $this->assertCount(5, JobSeeker::all());

        $this->json('post', route('api.matches', $jobOpening->id), [
            'matches' => [
                $jobSeekers->get(0)->id,
                $jobSeekers->get(1)->id,
            ]
        ])->assertStatus(200);

        $this->assertEquals(Match::STATUS_CANDIDATE, $jobOpening->matches()->first()->pivot->status);
    }
}
