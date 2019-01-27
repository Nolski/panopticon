<?php

namespace Tests\Unit;

use App\Models\Followup;
use App\Models\JobSeeker;
use App\Models\RecentActivity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CleanupAfterCaseRemovalTest extends TestCase
{

    use RefreshDatabase;

    public function test_remove_job_seekers_should_cleanup_followups()
    {
        $this->syncStructure("job-seeker");

        $this->syncCaseData('job-seeker');

        $jobSeeker = JobSeeker::first();

        $query = Followup::where('followup_type', get_class($jobSeeker))->where('followup_id', $jobSeeker->id);

        $this->assertCount(count(config('case.job-seeker.followup_schedule',[])),$query->get());

        $jobSeeker->delete();

        $this->assertCount(0, $query->get());
    }


    public function test_remove_job_seekers_should_cleanup_recent_activities()
    {
        $this->syncStructure("job-seeker");

        $this->syncCaseData('job-seeker');

        $jobSeeker = JobSeeker::first();


        $jobSeeker->recentActivities()->create([
            'title' => 'something'
        ]);

        $query = RecentActivity::where('entity_type', get_class($jobSeeker))->where('entity_id', $jobSeeker->id);

        $this->assertCount(1,$query->get());

        $jobSeeker->delete();

        $this->assertCount(0, $query->get());
    }



}
