<?php

declare(strict_types=1);

namespace App\controllers;

use App\http\HttpClient;
use App\requests\UserMeasure;

class MeasureController
{
    private const MEASURE_ENDPOINT = "/measure";


    public function __construct(
        private string $wbsapiUrl,
        private string $accessToken
    ) {}

    public function getMeasures(int $measureType = 1)
    {

        $client = new HttpClient($this->wbsapiUrl, [
            "Authorization: Bearer $this->accessToken"
        ]);

        // $measureTypes = $_GET['types'];
        $payload = new UserMeasure(
            (int)$measureType,
            startDate: 1710868877
        );


        $response = $client->request("POST", $this::MEASURE_ENDPOINT, $payload->asParams());

        $data = $response['body']['measuregrps'][0]['measures']['value'];
        return $data;
    }
}
