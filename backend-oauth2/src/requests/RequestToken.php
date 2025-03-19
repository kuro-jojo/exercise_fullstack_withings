<?php

declare(strict_types=1);

namespace App\requests;

class RequestToken
{
    private const ACTION = "requesttoken";

    public function __construct(
        private string $clientID,
        private string $clientSecret,
        private string $code,
        private string $redirectUri
    ) {}

    public function asParams(): array
    {

        return [
            'action' => $this::ACTION,
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUri,
            'code' => $this->code
        ];
    }
}
