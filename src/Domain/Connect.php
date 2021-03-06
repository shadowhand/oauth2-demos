<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\Payload;

class Connect extends AbstractProviderDomain
{
    public function __invoke(array $input)
    {
        $url = $this->provider->getAuthorizationUrl();
        $this->session->set('state', $this->provider->getState());

        return $this->payload()
            ->withStatus(Payload::STATUS_FOUND)
            ->withMessages([
                'redirect' => $url,
            ]);
    }
}
