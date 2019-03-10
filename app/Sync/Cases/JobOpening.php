<?php namespace App\Sync\Cases;

class JobOpening extends AbstractCase
{
    public $model = \App\Models\JobOpening::class;

    /**
     * CommCare module ID
     *
     * @return mixed
     */
    public function id(): string
    {
        // Job opening belongs to firm
        return 'e2a4b4924d5675493681b3f3ce63c4ed94430d8e';
    }

    /**
     * CommCare questions
     *
     * @return array
     */
    public function questions(): array
    {
        return [
            'job_id' => [
                'column_name' => 'job_id',
                'column_type' => 'string',
                'translations' => [
                    'en' => trans('irc.job_id',[],'en'),
                    'ara' => trans('irc.job_id',[], 'ar')
                ],
                'property' => true,
                'type' => 'text',
            ],
            'vtit' => [
                'column_name' => 'job_title',
                'column_type' => 'text',
                'alias' => 'job_title'
            ],
            'firm_id' => [
                'column_name' => 'firm_id',
                'column_type' => 'unsignedInteger',
                'valueHandler' => \App\Sync\QuestionHandler\JobOpeningFirmIdHandler::class,
                'property' => true,
                'type' => 'select',
                'translations' => [
                    'en' => trans('irc.firm_name',[],'en'),
                    'ara' => trans('irc.firm_name',[], 'ar')
                ],
            ],
            'date_opened' => [
                'column_name' => 'date_opened',
                'column_type' => 'dateTime',
                'property' => true,
                'type' => 'date',
                'translations' => [
                    'en' => trans('irc.date_opened',[],'en'),
                    'ara' => trans('irc.date_opened',[], 'ar')
                ],
                'valueHandler' => \App\Sync\QuestionHandler\DateHandler::class,
            ],
            'date_required' => [
                'column_name' => 'date_required',
                'column_type' => 'date',
            ],
            'vdesc' => [
                'column_name' => 'job_description',
                'column_type' => 'text',
                'alias' => 'job_description'
            ],
            'pno' => [
                'column_name' => 'num_vacancies',
                'column_type' => 'text',
                'alias' => 'num_vacancies'
            ],
            'coworker_nationality' => [
                'column_name' => 'coworker_nationality',
                'column_type' => 'text',
                'alias' => 'coworker_nationality'
            ],
            'coworkers_gender' => [
                'column_name' => 'coworker_gender',
                'column_type' => 'text',
                'alias' => 'coworker_gender'
            ],
            'manager_gender' => [
                'column_name' => 'manager_gender',
                'column_type' => 'text',
                'alias' => 'manager_gender'
            ],
            'firm_name' => [
                'column_name' => 'firm_name',
                'column_type' => 'text',
                'alias' => 'firm_name',
                'translations' => [
                    'en' => trans('irc.firm_name',[],'en'),
                    'ara' => trans('irc.firm_name',[], 'ar')
                ],
            ]
//            'employed' => [
//                'column_name' => 'employed',
//                'column_type' => 'string'
//            ]
        ];
    }

    public function formId()
    {
        return [
            '7c99a14775e998cba17c353a26de887715e32b74'
        ];
    }
}
