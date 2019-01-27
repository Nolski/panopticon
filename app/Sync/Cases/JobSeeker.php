<?php namespace App\Sync\Cases;


use App\Sync\QuestionHandler\ActualInterventionReceivedHandler;

class JobSeeker extends AbstractCase
{
    public $model = \App\Models\JobSeeker::class;

    /**
     * CommCare module ID
     *
     * @return mixed
     */
    public function id(): string
    {
        return 'c34aa2e60c9858cc95aaeb9a263c2eeba1472e70';
    }

    /**
     * CommCare questions
     *
     * @return array
     */
    public function questions(): array
    {
        return [
            'first_name' => [
                'column_name' => 'first_name',
                'column_type' => 'string'
            ],
            'last_name' => [
                'column_name' => 'last_name',
                'column_type' => 'string'
            ],

            'city' => [
                'column_name' => 'city',
                'column_type' => 'string',
            ],
            'district' => [
                'column_name' => 'district',
                'column_type' => 'string'
            ],
            'nationality' => [
                'column_name' => 'nationality',
                'column_type' => 'string'
            ],
            'age' => [
                'column_name' => 'age',
                'column_type' => 'string'
            ],
            'will_work_qiz' => [
                'column_name' => 'will_work_qiz',
                'column_type' => 'string'
            ],
            'mobile_num' => [
                'column_name' => 'mobile_num',
                'column_type' => 'string'
            ],
            'gender' => [
                'column_name' => 'gender',
                'column_type' => 'string'
            ],
            'eso_id' => [
                'column_name' => 'eso_id',
                'column_type' => 'text',
            ],
            'first_preference' => [
                'column_name' => 'first_preference',
                'column_type' => 'string',
                'alias' => 'first_job_field_preference'
            ],
            'second_preference' => [
                'column_name' => 'second_preference',
                'column_type' => 'string',
                'alias' => 'second_job_field_preference'
            ],
            'national_id' => [
                'column_name' => 'national_id',
                'column_type' => 'string',
            ],
            'moi' => [
                'column_name' => 'moi',
                'column_type' => 'string',
            ],
            'actual_intervention_received' => [
                'column_name' => 'actual_intervention_received',
                'column_type' => 'string',
                'valueHandler' => ActualInterventionReceivedHandler::class
            ],

        ];
    }

    public function formId()
    {
        return '37f43427d24bd6a294fdd4bf7e3c45fdace489a1';
    }
}