<?php namespace Tests\Fixtures\Classes;

use Tests\Fixtures\Classes\Cases\DefaultValue;
use Tests\Fixtures\Classes\Cases\Firm;
use Tests\Fixtures\Classes\Cases\JobOpening;
use Tests\Fixtures\Classes\Cases\JobSeeker;
use Tests\Fixtures\Classes\Cases\Match;

class StructureFactory extends \App\Sync\StructureFactory
{
    protected $caseTypes = [
        Firm::class,
        Match::class,
        JobSeeker::class,
        JobOpening::class,
        DefaultValue::class
    ];
}