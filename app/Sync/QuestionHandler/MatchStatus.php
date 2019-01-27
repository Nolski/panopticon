<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace App\Sync\QuestionHandler;


use App\Models\Match;
use App\Sync\Cases\AbstractCase;
use Carbon\Carbon;

class MatchStatus implements HandlerInterface
{
    public function handle($caseData, $questionId, $question, AbstractCase $case)
    {
        $isHired = array_get($caseData,'properties.hired_yes_no');

        if(!empty($isHired)){
            $isHired = boolval(intval($isHired));
        }else{
            $isHired = false;
        }

        if($isHired){
            $matched = Match::STATUS_HIRED;
        }else{
            $matched = Match::STATUS_MATCHED;
        }

        return $this->resolve($matched);
    }

    public function resolve($value)
    {
        return $value;
    }
}