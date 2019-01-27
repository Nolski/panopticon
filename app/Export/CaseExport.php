<?php


namespace App\Export;

use Maatwebsite\Excel\Facades\Excel;

class CaseExport extends Export
{

    protected $headers = ['id'];

    /**
     * @return array|mixed
     */
    public function getHeaders()
    {
        $headers = $this->data->with(request())['headers'];

        $headers = array_map(function ($header) {
            $this->headers[] = $header['name'];
            return $header['label'];
        }, $headers);

        array_unshift($headers, 'id');

        return $headers;
    }

    /**
     * @return array|mixed
     */
    public function getData()
    {
        $responses = $this->data->toArray(request());

        return array_map(function ($response) {
            return array_only_sorted_by_keys($response, $this->headers);
        }, $responses);
    }
}
