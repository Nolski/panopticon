<?php namespace App\Sync\Cases;

use App\Sync\QuestionHandler\MatchFirmIdHandler;
use App\Sync\QuestionHandler\MatchIsCandidate;
use App\Sync\QuestionHandler\MatchJobOpeningIdHandler;
use App\Sync\QuestionHandler\MatchJobSeekerIdHandler;
use App\Sync\QuestionHandler\MatchStatus;

class Match extends AbstractCase
{
    public $model = \App\Models\Match::class;

    /**
     * CommCare module ID
     *
     * @return mixed
     */
    public function id(): string
    {
        return '562c52af2c135d2e1dfa57f15a478c676a6eafee';
    }

    /**
     * CommCare questions
     *
     * @return array
     */
    public function questions(): array
    {
        return [
            'job_opening_id' => [
                'column_name' => 'job_opening_id',
                'column_type' => 'unsignedInteger',
                'property' => true,
                'type' => 'select',
                'translations' => [
                    'en' => 'Job Opening',
                    'ara' => 'Job Opening'
                ],
                'valueHandler' => MatchJobOpeningIdHandler::class
            ],
            'job_seeker_id' => [
                'column_name' => 'job_seeker_id',
                'column_type' => 'unsignedInteger',
                'property' => true,
                'type' => 'select',
                'translations' => [
                    'en' => 'Job Seeker',
                    'ara' => 'Job Seeker'
                ],
                'valueHandler' => MatchJobSeekerIdHandler::class
            ],
            'firm_id' => [
                'column_name' => 'firm_id',
                'column_type' => 'unsignedInteger',
                'property' => true,
                'type' => 'select',
                'translations' => [
                    'en' => 'Firm',
                    'ara' => 'Firm'
                ],
                'valueHandler' => MatchFirmIdHandler::class
            ],
            'is_candidate' => [
                'column_name' => 'is_candidate',
                'column_type' => 'boolean',
                'property' => true,
                'type' => 'select',
                'translations' => [
                    'en' => 'Is Candidate',
                    'ara' => 'Is Candidate'
                ],
                'valueHandler' => MatchIsCandidate::class
            ],

            'status' => [
                'column_name' => 'status',
                'column_type' => 'string',
                'property' => true,
                'type' => 'select',
                'translations' => [
                    'en' => 'Status',
                    'ara' => 'Status'
                ],
                'valueHandler' => MatchStatus::class,
                'default' => \App\Models\Match::STATUS_CANDIDATE
            ],
        ];
    }

    public function caseType(): string
    {
        return 'match';
    }

    public function canSave($case): bool
    {
        $questions = $this->questions();

        $jobOpeningId = app(MatchJobOpeningIdHandler::class)->handle(
            $case, 'job_opening_id', $questions['job_opening_id'], $this
        );

        $jobSeekerId = app(MatchJobSeekerIdHandler::class)->handle(
            $case, 'job_seeker_id', $questions['job_seeker_id'], $this
        );

        if(empty($jobOpeningId) || empty($jobSeekerId)){
            return false;
        }

        return true;

        return \App\Models\Match::where('job_seeker_id',$jobSeekerId)->where('job_opening_id',$jobOpeningId)->count() === 1;
    }

    public function savingKeys($data)
    {
        return array_only($data, ['job_seeker_id','job_opening_id']);
    }
}