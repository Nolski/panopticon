<?php namespace App\Sync\Cases;


use App\Sync\QuestionHandler\ActualInterventionReceivedHandler;
use App\Sync\QuestionHandler\CaseIdHandler;
use App\Sync\QuestionHandler\DateHandler;

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
            'opened_date' => [
                'column_name' => 'opened_at',
                'create_column' => false,
                'column_type' => 'datetime',
                'valueHandler' => DateHandler::class
            ],
            'caseid' => [
                'column_name' => 'commcare_id',
                'create_column' => false,
                'column_type' => 'string',
                'valueHandler' => CaseIdHandler::class
            ],
            'sec_contact_mobile' => [
                'column_name' => 'sec_contact_mobile',
                'column_type' => 'string',
            ],
            'marital_status' => [
                'column_name' => 'marital_status',
                'column_type' => 'string',
            ],
            'neighborhood' => [
                'column_name' => 'neighborhood',
                'column_type' => 'string',
                'alias' => 'neighbourhood'
            ],
            'benefit1' => [
                'column_name' => 'benefit1',
                'column_type' => 'string',
            ],
            'benefit2' => [
                'column_name' => 'benefit2',
                'column_type' => 'string',
            ],
            'completed_training_courses' => [
                'column_name' => 'completed_training_courses',
                'column_type' => 'string',
            ],
            'completed_university' => [
                'column_name' => 'completed_university',
                'column_type' => 'string',
                'alias' => 'university',
            ],
            'computer_exprience' => [
                'column_name' => 'computer_exprience',
                'column_type' => 'string',
            ],
            'daily_hours_willing_to_work' => [
                'column_name' => 'daily_hours_willing_to_work',
                'column_type' => 'string',
            ],
            'distance_willing_to_travel' => [
                'column_name' => 'distance_willing_to_travel',
                'column_type' => 'string',
                'alias' => 'trip_to_city_time',
            ],
            'email' => [
                'column_name' => 'email',
                'column_type' => 'string',
            ],
            'english_proficiency' => [
                'column_name' => 'english_proficiency',
                'column_type' => 'string',
            ],
            'expected_wage' => [
                'column_name' => 'expected_wage',
                'column_type' => 'string',
            ],
            'gendermix_not_allowed' => [
                'column_name' => 'gendermix_not_allowed',
                'column_type' => 'string',
                'alias' => 'opposite_gender_coworkers'
            ],
            'has_job' => [
                'column_name' => 'has_job',
                'column_type' => 'string',
            ],
            'health_impairments_calc' => [
                'column_name' => 'health_impairments_calc',
                'column_type' => 'string',
                'alias' => 'health_condition_calc'
            ],
            'interview_date' => [
                'column_name' => 'interview_date',
                'column_type' => 'string',
            ],
            'job_sector' => [
                'column_name' => 'job_sector',
                'column_type' => 'string',
            ],
            'major' => [
                'column_name' => 'major',
                'column_type' => 'string',
            ],
            'origin_country' => [
                'column_name' => 'origin_country',
                'column_type' => 'string',
            ],
            'personal_avg_monthly_income' => [
                'column_name' => 'personal_income',
                'column_type' => 'string',
                'alias' => 'personal_income'
            ],
            'will_do_physical_work' => [
                'column_name' => 'physical_activity',
                'column_type' => 'string',
                'alias' => 'physical_activity'
            ],

            'prev_job_description' => [
                'column_name' => 'prev_job_description',
                'column_type' => 'string',
            ],
            'prev_num_jobs' => [
                'column_name' => 'prev_num_jobs',
                'column_type' => 'string',
            ],
            'sec_contact_name' => [
                'column_name' => 'sec_contact_name',
                'column_type' => 'string',
            ],
            'sec_contact_relation' => [
                'column_name' => 'sec_contact_relation',
                'column_type' => 'string',
            ],

            'weekly_days_willing_to_work' => [
                'column_name' => 'weekly_days_willing_to_work',
                'column_type' => 'string',
                'alias' => 'days_of_work_per_week'
            ],
            'will_live_in_dorm' => [
                'column_name' => 'will_live_in_dorm',
                'column_type' => 'string',
            ],
            'will_train_unpaid' => [
                'column_name' => 'will_train_unpaid',
                'column_type' => 'string',
            ],
            'will_work_night_shift' => [
                'column_name' => 'will_work_night_shift',
                'column_type' => 'string',
            ],
            'job_workspace' => [
                'column_name' => 'job_workspace',
                'column_type' => 'string',
                'alias' => 'workspace_preference'
            ],
            'years_exp' => [
                'column_name' => 'years_exp',
                'column_type' => 'string',
                'translations' => [
                    'en' => trans('irc.years_exp',[],'en'),
                ],
            ],


//            'prev_job_management' => [
//                'column_name' => 'prev_job_management',
//                'column_type' => 'string',
//            ],
//            'days_willing_train_unpaid' => [
//                'column_name' => 'days_willing_train_unpaid',
//                'column_type' => 'string',
//            ],
//            'trip_to_city_time' => [
//                'column_name' => 'trip_to_city_time',
//                'column_type' => 'string',
//            ],

//            'owes_community_personal_loan' => [
//                'column_name' => 'owes_community_personal_loan',
//                'column_type' => 'string',
//            ],
//            'experience_professional_work' => [
//                'column_name' => 'experience_professional_work',
//                'column_type' => 'string',
//            ],

//            'opposite_gender_coworkers' => [
//                'column_name' => 'opposite_gender_coworkers',
//                'column_type' => 'string',
//            ],
            'earliest_start_date' => [
                'column_name' => 'earliest_start_date',
                'column_type' => 'string',
                'alias' => 'earliest_start_time'
            ],
        ];
    }

    public function formId()
    {
        return '37f43427d24bd6a294fdd4bf7e3c45fdace489a1';
    }
}
