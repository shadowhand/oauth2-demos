<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\Payload;

class Logout extends AbstractProviderDomain
{
    public function __invoke(array $input)
    {
        $this->storeToken($input['provider'], null);

        return $this->payload()
            ->withStatus(Payload::STATUS_FOUND)
            ->withMessages([
                'redirect' => '/',
            ]);
    }
}
