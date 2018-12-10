<?php namespace App\Sync\Cases;

class JobSeeker extends AbstractCase
{

    public function handle($data)
    {
        dd($this->prepareProperties($data[0]['properties']));
    }

    /**
     * Case type required for to call endpoint filtered by class case type
     *
     * @return string
     */
    public function caseType(): string
    {
        return 'job-seeker';
    }
}