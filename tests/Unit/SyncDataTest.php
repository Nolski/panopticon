<?php namespace Tests\Unit;

use Tests\TestCase;
use App\Models\JobSeeker;
use Tests\Fixtures\Classes\DataFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_data_can_create_to_database()
    {
        $this->syncCaseData('job-seeker');

        $this->assertNotEmpty(JobSeeker::all());
    }


    public function test_it_saves_case_opened_at()
    {
        $this->syncCaseData('job-seeker');

        $this->assertEquals(
            '2018-08-13 07:53:00',
            JobSeeker::first()->opened_at
        );
    }


    public function test_is_assign_followups_for_jobseekers()
    {

        // replace schedule to prevent test fail on changing the config
        config(['case.job-seeker.followup_schedule' => [
            '1_month' => '+1 Month',
            '6_weeks' => '+6 Weeks',
            '3_months' => '+3 Month',
            '6_months' => '+6 Months'
        ]]);

        $this->syncUsers();

        $this->syncCaseData('job-seeker');

        $jobSeeker = JobSeeker::first();

        $this->assertCount(count(config('case.job-seeker.followup_schedule')), $jobSeeker->followups);

        $this->assertEquals(
            \Carbon\Carbon::parse($jobSeeker->opened_at)
                ->modify(config('case.job-seeker.followup_schedule.1_month'))
                ->toDateString(),
            $jobSeeker->followups->where('followup_period', '1_month')->first()->followup_date
        );

        $this->assertEquals(
            \Carbon\Carbon::parse($jobSeeker->opened_at)
                ->modify(config('case.job-seeker.followup_schedule.6_weeks'))
                ->toDateString(),
            $jobSeeker->followups->where('followup_period', '6_weeks')->first()->followup_date
        );

        $this->assertEquals(
            \Carbon\Carbon::parse($jobSeeker->opened_at)
                ->modify(config('case.job-seeker.followup_schedule.3_months'))
                ->toDateString(),
            $jobSeeker->followups->where('followup_period', '3_months')->first()->followup_date
        );

        $this->assertEquals(
            \Carbon\Carbon::parse($jobSeeker->opened_at)
                ->modify(config('case.job-seeker.followup_schedule.6_months'))
                ->toDateString(),
            $jobSeeker->followups->where('followup_period', '6_months')->first()->followup_date
        );

        $this->assertNotEmpty($jobSeeker->followups->first()->user_id);
    }


    public function test_it_will_not_duplicated_followups()
    {
        // replace schedule to prevent test fail on changing the config
        config(['case.job-seeker.followup_schedule' => [
            '1_month' => '+1 Month',
            '6_weeks' => '+6 Weeks',
            '3_months' => '+3 Month',
            '6_months' => '+6 Months'
        ]]);

        $this->syncUsers();

        $this->syncCaseData('job-seeker');

        $jobSeeker = JobSeeker::first();

        $this->assertCount(count(config('case.job-seeker.followup_schedule')), $jobSeeker->followups);

        $this->syncCaseData('job-seeker');

        $jobSeeker = JobSeeker::first();

        $this->assertCount(count(config('case.job-seeker.followup_schedule')), $jobSeeker->followups);

    }

    protected function syncCaseData($type)
    {

        $this->syncStructure($type);

        $this->mockDataRequest();

        $factory = app(DataFactory::class);

        $factory->make($type);
    }
}