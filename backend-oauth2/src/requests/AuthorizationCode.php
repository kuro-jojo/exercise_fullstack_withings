<?php

namespace App\requests;

class AuthorizationCode
{

    public function __construct(
        private string $clientID,
        private string $state,
        private string $scope,
        private string $redirectUri,
        private string $responseType = "code"
    ) {}

    public function asParams(): array
    {

        return [
            'response_type' => 'code',
            'client_id' => $this->clientID,
            'scope' => $this->scope,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state,
            'mode' => 'demo'
        ];
    }
}
