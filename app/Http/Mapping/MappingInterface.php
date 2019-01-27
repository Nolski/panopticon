<?php namespace App\Http\Mapping;

use Illuminate\Database\Eloquent\Builder;

interface MappingInterface
{
    public function handle(Builder $builder);
}