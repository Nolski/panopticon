<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace App\Sync\QuestionHandler;

use App\Sync\Cases\AbstractCase;

class CaseIdHandler implements HandlerInterface
{
    public function handle($caseData, $questionId, $question, AbstractCase $case)
    {
        $caseId = array_get($caseData, 'id');

        return $this->resolve($caseId);
    }

    public function resolve($value)
    {
        return $value;
    }
}