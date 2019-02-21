<?php namespace App\Sync;

class MatchScoresRequest extends Request
{

    /**
     * @var string
     */
    protected $url = 'https://theirc-tashbeek-staging.azurewebsites.net/job-matches/';

    /**
     * @param array $jopOpeningIds
     * @return mixed
     */
    public function data(array $jopOpeningIds = [])
    {
        $urls = $this->makeUrls($jopOpeningIds);

        $options = [
            'headers' => [
                'Content-Type' => 'text/xml',
                'Authorization' => 'Token ' . config('irc.ml_api_key')
            ],
        ];

        return $this->multiRequest($urls, $options);
    }

    /**
     * @param $jopOpeningIds
     * @return array
     */
    public function makeUrls($jopOpeningIds)
    {
        $urls = [];

        foreach ($jopOpeningIds as $jopOpeningId) {
            $urls[$jopOpeningId] = $this->url . '?' . http_build_query(['job_id' => $jopOpeningId]);
        }

        return $urls;
    }
}

