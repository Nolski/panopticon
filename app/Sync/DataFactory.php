<?php namespace App\Sync;

use App\Models\User;
use App\Sync\Cases\AbstractCase;
use App\Sync\Cases\Firm;
use App\Sync\Cases\Match;
use App\Sync\Cases\JobOpening;
use App\Sync\Cases\JobSeeker;
use App\Sync\QuestionHandler\DateHandler;

class DataFactory
{
    use CaseVerification;

    protected $caseTypes = [
        Firm::class,
        JobSeeker::class,
        JobOpening::class,
        Match::class
    ];

    /**
     * @var StructureRequest
     */
    protected $request;

    /**
     * StructureFactory constructor.
     * @param DataRequest $request
     */
    public function __construct(DataRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Sync case data to database
     *
     * @param null $caseType
     * @param int $page
     * @throws \Throwable
     */
    public function make($caseType, $page = 1)
    {
        $this->verifyCaseType($caseType);

        $case = app($this->getClassFromType($caseType));

        $response = $this->request->data($case->caseType(), ['page' => $page]);

        $this->saveItems($case, $response['objects']);

        if (!is_null($response['meta']['next'])) {
            $this->make($caseType, $page + 1);
        }
    }

    /**
     * Save multiple cases data
     *
     * @param AbstractCase $caseObject
     * @param $cases
     * @throws \Exception
     */
    protected function saveItems(AbstractCase $caseObject, $cases)
    {
        foreach ($cases as $case) {
            $this->saveItem($caseObject, $case);
        }
    }

    /**
     * Save single case data
     *
     * @param $caseObject
     * @param $case
     * @throws \Exception
     */
    protected function saveItem($caseObject, $case)
    {
        if(!$caseObject->canSave($case)){
            return ;
        }

        if($this->removeClosedCase($case, $caseObject)){
            return ;
        }

        $data = $this->getCaseFields($caseObject, $case);

        $model = $caseObject->model()::updateOrCreate($caseObject->savingKeys($data), $data);

        $this->createNotes($model, $case);

        $this->scheduleFollowups($model);
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

    /**
     * Map CommCare fields to database fields
     *
     * @param AbstractCase $caseObject
     * @param $case
     * @return array
     * @throws \Exception
     */
    protected function getCaseFields(AbstractCase $caseObject, $case)
    {
        $data = [];

        foreach ($caseObject->questions() as $questionId => $question) {

            $value = array_get(array_get($case, 'properties'), $questionId);

            if(empty($value) && isset($question['alias'])){
                $value = array_get(array_get($case, 'properties'), $question['alias']);
            }

            if(isset($question['valueHandler'])){
                $value = app($question['valueHandler'])->handle($case, $questionId, $question, $caseObject);
            }

            $data[$question['column_name']] = $value;
        }

        $data['opened_at'] = app(DateHandler::class)->resolve(array_get($case, 'server_date_opened'));

        $data['commcare_id'] = $case['id'];

        $userId = array_get($case,'opened_by', $case['user_id']);

        $data['user_id'] = optional(User::where('commcare_id',$userId)->first())->id ;

        return $data;
    }

    /**
     * Remove closed case from our database
     *
     * @param $case
     * @param $caseObject
     * @return bool
     */
    protected function removeClosedCase($case, $caseObject)
    {
        if(array_get($case,'closed') === true){
            $m = $caseObject->model()::where('commcare_id',$case['id'])->first();

            if($m){
                $m->delete();
            }

            return true;
        }

        return false;
    }

    /**
     * Schedule
     * @param $model
     */
    protected function scheduleFollowups($model)
    {
        if(!method_exists($model,'followups') || empty($model->opened_at)){
            return ;
        }

        $caseType = case_type($model);

        foreach(config('case.'.$caseType.'.followup_schedule') as $key => $schedule){

            $followUp = $model->followups()->where('type','scheduled')->where('followup_period',$key)->first();

            if(!$followUp){
                $model->followups()->create([
                    'followup_date' => \Carbon\Carbon::parse($model->opened_at)->modify($schedule)->toDateString(),
                    'followup_period' => $key,
                    'type' => 'scheduled',
                    'user_id' => $model->user_id,
                ]);
            }
        }
    }

    public function createNotes($model, $case)
    {
        $esoComments = array_get($case, 'properties.eso_comments');

        if(!empty(trim($esoComments))){
            if(method_exists($model,'notes')){
                $note = $model->notes()->where('commcare_id',$model->commcare_id )->first();
                if(!$note){
                    $model->notes()->create([
                        'note' => $esoComments,
                        'user_id' => $model->user_id,
                        'commcare_id' => $model->commcare_id,
                        'created_at' => $model->opened_at,
                    ]);
                }else{
                    $note->update([
                        'note' => $esoComments,
                    ]);
                }
            }
        }
    }
}