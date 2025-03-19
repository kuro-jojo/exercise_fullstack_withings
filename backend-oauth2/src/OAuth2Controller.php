<?php

declare(strict_types=1);

namespace App\controllers;

session_start();

use App\http\HttpClient;
use App\requests\AuthorizationCode;
use App\requests\RequestToken;
use DateInterval;
use DateTime;

class OAuth2Controller
{
    private const OAUTH2_ENDPOINT = "/oauth2";
    private const AUTHORIZATION_ENDPOINT = "/oauth2_user/authorize2";

    private string $scope = "user.metrics";

    public function __construct(
        private string $clientID,
        private string $clientSecret,
        private string $redirectURI,
        private string $wbsapiUrl,
        private string $accountUrl,
        private string $state,

    ) {}

    public function requestAuthorizationCode()
    {
        $payload = new AuthorizationCode(
            $this->clientID,
            $this->state,
            $this->scope,
            $this->redirectURI
        );

        $url = $this->accountUrl . $this::AUTHORIZATION_ENDPOINT . '?' . http_build_query($payload->asParams());

        header("Location: $url");
        exit;
    }

    // callback function
    public function getRequestToken(): string | null
    {

        $code = $_GET['code'];

        if (!isset($code)) {
            return null;
        }

        $state = $_GET['state']; // TODO check the state
        $client = new HttpClient($this->wbsapiUrl);

        $payload = new RequestToken(
            $this->clientID,
            $this->clientSecret,
            $code,
            $this->redirectURI
        );

        $response = $client->request("POST", $this::OAUTH2_ENDPOINT, $payload->asParams());

        if (isset($response['status'])) {
            $status = (int)$response['status'];
            if ($status != 0) {
                return null;
            }
        }

        $accessToken = $response['body']['access_token'];
        $expiresIn = $response['body']['expires_in'];
        $expiresAt = new DateTime();
        $expiresAt->add(DateInterval::createFromDateString($expiresIn . " sec"));

        // Store the access token in the session
        $_SESSION['access_token'] = $accessToken;
        $_SESSION['expires_at'] = $expiresAt;

        return $response['access_token'];
    }
}
