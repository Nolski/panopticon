<?php

namespace App\Sync;


use Illuminate\Support\Facades\Schema as LaravelSchema;
use Illuminate\Database\Schema\Blueprint;

class Schema
{
    public function generate($tableName, $fields)
    {
        $method = LaravelSchema::hasTable($tableName) ? 'table' : 'create';

        LaravelSchema::$method($tableName, function (Blueprint $table) use ($fields,$method) {

            if($method === 'create'){
                $table->increments('id');
            }

            foreach ($fields as $field) {

                if(!array_get($field,'create_column', true)){
                    continue;
                }

                $column = $table->{$field['column_type']}($field['column_name'])->nullable();

                $defaultValue = array_get($field,'default');

                if($defaultValue){
                    $column->default($defaultValue);
                }

                if ($method === 'table' && LaravelSchema::hasColumn($table->getTable(), $field['column_name'])) {
                    $column->change();
                }
            }

            if ($method === 'create' || !LaravelSchema::hasColumn($table->getTable(), 'commcare_id')) {
                $table->string('commcare_id', 60)->index()->nullable()->default(null);
            }

            if ($method === 'create') {
                $table->timestamps();
            }

            if($method === 'create' ||  !LaravelSchema::hasColumn($table->getTable(), 'opened_at')){
                $table->timestamp('opened_at')->nullable();
            }

            if($method === 'user_id' ||  !LaravelSchema::hasColumn($table->getTable(), 'user_id')){
                $table->unsignedInteger('user_id')->nullable()->default(null);
            }
        });
    }
}