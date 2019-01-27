<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace App\Sync\QuestionHandler;

use App\Sync\Cases\AbstractCase;


class ActualInterventionReceivedHandler implements HandlerInterface
{
    public function handle($caseData, $questionId, $question, AbstractCase $case)
    {
        $value = array_get(array_get($caseData, 'properties'), $questionId);

        if(empty($value)){
            $value = 'none';
        }

        return $this->resolve($value);
    }

    public function resolve($value)
    {
        return $value;
    }
}