<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace App\Sync;


use App\Models\PropertyMetaData as PropertyMetaDataModel;
use App\Models\PropertyOption;

class PropertiesMetaData
{
    public function insert($questions, $caseType, $caseQuestions)
    {
        $order = 1;

        foreach ($questions as $question) {

            $baseCommcareFieldName = base_commcare_field_name($question['hashtagValue']);

            $keys = ['commcare_id' => $baseCommcareFieldName, 'case_type' => $caseType];

            $values = [
                'column_name' => $question['case_question']['column_name'],
                'case_type' => $caseType,
                'attributes' => $this->getAttributes($question),
                'has_filter' => $caseQuestions[$baseCommcareFieldName]['has_filter'] ?? false,
                'order' => $order
            ];

            $property = PropertyMetaDataModel::query()->updateOrCreate($keys, $values);

            $this->insertOptions($property, $question);

            $order++;
        }
    }

    public function insertOptions($property, $question)
    {

        foreach ($question['options'] ?? [] as $option) {

            $keys = ['commcare_id' => $option['value']];

            $values = ['name' => $option['translations']];

            $property->options()->updateOrCreate($keys, $values);
        }

        $property->options()->whereNotIn('commcare_id', array_pluck($question['options'] ?? [], 'value'))->forceDelete();
    }


    protected function getAttributes($question)
    {
        return array_only($question, ['label', 'options', 'constraint', 'type', 'translations']);
    }


}