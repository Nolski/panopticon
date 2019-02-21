<?php namespace App\Sync;

use App\Models\User;
use App\Sync\Cases\AbstractCase;
use App\Sync\Cases\Firm;
use App\Sync\Cases\Match;
use App\Sync\Cases\JobOpening;
use App\Sync\Cases\JobSeeker;
use App\Sync\QuestionHandler\DateHandler;

class RecentActivityFactory
{

    const FORMS = [
        'job-seeker-monthly-followup' => [
            'case_type' => 'job-seeker',
            'form_id' => '1C0909C3-286E-4EAA-BB12-79D5758366BE',
        ],

        'firm-monthly-followup' => [
            'case_type' => 'firm',
            'form_id' => '192695F5-F1BE-431B-8DE7-4302C02AB020',
        ],
    ];
    /**
     * @var StructureRequest
     */
    protected $request;

    /**
     * StructureFactory constructor.
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Sync case data to database
     *
     * @param $form
     * @param int $page
     * @throws \Throwable
     */
    public function make($formName, $page = 1)
    {

        if(!array_key_exists($formName,static::FORMS)){
            throw new \InvalidArgumentException("$formName is invalid");
        }

        $form = static::FORMS[$formName];

        if(!isset($form['case_type'])){
            throw new \InvalidArgumentException("case_type is not exists in {$formName} definition.");
        }

        if(!isset($form['form_id'])){
            throw new \InvalidArgumentException("form_id is not exists in {$formName} definition.");
        }

        $xmlns = $form['form_id'];

        $caseType = $form['case_type'];

        $response = $this->request->data([
            'page' => $page,
            'xmlns' => 'http://openrosa.org/formdesigner/' . $xmlns
        ]);

        $this->saveItems($caseType, $response['objects']);

        if (!is_null($response['meta']['next'])) {
            $this->make($formName, $page + 1);
        }
    }

    /**
     * Save multiple cases data
     *
     * @param $cases
     * @throws \Exception
     */
    protected function saveItems($caseType, $cases)
    {
        foreach ($cases as $case) {
            $this->saveItem($caseType, $case);
        }
    }

    /**
     * Save single case data
     *
     * @param $caseType
     * @param $form
     */
    protected function saveItem($caseType, $form)
    {
        $model = get_case_type_model($caseType);

        $commCareId = array_get($form,'form.case.@case_id');

        $query = $model->query()->where('commcare_id', $commCareId);

        $record = $query->first();

        if(!$record){
            return;
        }

        if($record->recentActivities()->where('commcare_id',$form['id'])->first()){
            return ;
        }

        $userId = null;

        if(!empty(array_get($form,'form.meta.userID'))){
            $userId = optional(User::where('commcare_id',array_get($form,'form.meta.userID'))->first())->id;
        };

        $record->recentActivities()->create([
            'commcare_id' => $form['id'],
            'title' => array_get($form,'form.@name'),
            'created_at' => app(DateHandler::class)->resolve($form['received_on']),
            'user_id' => $userId
        ]);

        $this->createNotes($record, $form);
    }

    /**
     * get the field id from fully qualified question path
     *
     * @param $path
     * @return mixed
     */
    protected function fetchQuestionIdFromPath($path)
    {
        $segments = explode('/', $path);

        return end($segments);
    }

    public function createNotes($model, $case)
    {
        $comment = array_get($case, 'form.ex.comments');

        if(!empty(trim($comment))){
            if(method_exists($model,'notes')){
                $note = $model->notes()->where('commcare_id',$model->commcare_id )->first();
                if(!$note){
                    $model->notes()->create([
                        'note' => $comment,
                        'user_id' => $model->user_id,
                        'commcare_id' => $model->commcare_id,
                        'created_at' => $model->created_at->toDateTimeString(),
                        'type' => 'follow_up'
                    ]);
                }else{
                    $note->update([
                        'note' => $comment,
                    ]);
                }
            }
        }
    }
}