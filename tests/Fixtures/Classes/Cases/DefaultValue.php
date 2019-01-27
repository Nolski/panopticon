<?php
/**
 * Created by Solaiman Kmail <psokmail@gmail.com>
 */

namespace Tests\Fixtures\Classes\Cases;


class DefaultValue extends \App\Sync\Cases\Firm
{
    public $model = \Tests\Fixtures\Classes\Models\DefaultValue::class;

    public function questions(): array
    {
        return [
            'default_column' => [
                'column_name' => 'default_column',
                'column_type' => 'text',
                'property' => true,
                'default' => "DefaultValue"
            ],
        ];
    }
}