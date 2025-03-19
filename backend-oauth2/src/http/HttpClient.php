<?php

namespace App\http;

class HttpClient
{
    private $baseUrl;
    private $headers = [];

    public function __construct(string $baseUrl, array $headers = [])
    {
        $this->baseUrl = rtrim($baseUrl, "/");
        $this->headers = $headers;
    }

    public function request(string $method, string $endpoint,  array $data = [], bool $asQuery = true): array
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if (!empty($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        if ($method === "POST" || $method === "PUT") {
            if ($asQuery) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return ["error" => "cURL error: $error"];
        }

        $response = json_decode($response, true);

        return $response;
    }
}
