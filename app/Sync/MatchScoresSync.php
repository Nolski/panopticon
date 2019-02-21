<?php namespace App\Sync;

use App\Models\JobOpening;
use App\Models\JobSeeker;
use App\Models\MatchScore;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MatchScoresSync
{
    /**
     * @var UsersRequest
     */
    private $request;
    /**
     * @var MobileWorkersRequest
     */
    private $mobileWorkersRequest;


    public function __construct(MatchScoresRequest $request)
    {
        $this->request = $request;
    }

    public function make()
    {
        $this->sync($this->request);
    }


    protected function sync($request)
    {
        $jopOpeningIds = $this->getJobOpeningIds();

        $responses = $request->data(array_keys($jopOpeningIds));

        $jobSeekerMapping = $this->jobSeekerMapping();

        foreach ($responses as $jobId => $response) {

            $response = array_get($response, 'value');

            if(!$response || $response->getStatusCode() !== 200){
                continue;
            }

            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);
            if($body['status'] === 'complete'){
                MatchScore::where('job_opening_id',$jopOpeningIds[$jobId])->delete();
                $this->saveMatches($body['scores'], $jobSeekerMapping, $jopOpeningIds[$jobId]);
            }
        }
    }

    /**
     * @param $jobSeekers
     * @param $jobSeekerMapping
     * @param $jobId
     */
    protected function saveMatches($jobSeekers, $jobSeekerMapping, $jobId)
    {
        foreach ($jobSeekers as $jobSeeker) {
            $jobSeekerId = $jobSeekerMapping[$jobSeeker['case_id']] ?? null;
            if ($jobSeekerId) {
                $this->saveMatch($jobSeekerId, $jobId, $jobSeeker['probs']);
            }
        }
    }


    /**
     * @param $jobSeekerId
     * @param $jobId
     * @param $score
     */
    protected function saveMatch($jobSeekerId, $jobId, $score)
    {
        MatchScore::query()
            ->updateOrCreate(['job_opening_id' => $jobId, 'job_seeker_id' => $jobSeekerId], ['score' => $score]);
    }

    /**
     *  Get Job Openings Ids
     *
     * @return array
     */
    protected function getJobOpeningIds()
    {
        return JobOpening::query()
            ->pluck('id', 'job_id')
            ->toArray();
    }


    protected function jobSeekerMapping()
    {
        return JobSeeker::query()
            ->pluck('id', 'commcare_id')
            ->toArray();
    }


}