<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\Adr\DomainInterface;
use Equip\SessionInterface;
use Equip\Payload;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class User extends AbstractProviderDomain
{
    private function isAuthenticated(array $input)
    {
        if (empty($input['provider']) || !$this->session->has('tokens')) {
            return false;
        }

        $provider = $input['provider'];
        $tokens = $this->session->get('tokens');

        return !empty($tokens[$provider]);
    }

    public function __invoke(array $input)
    {
        if (!$this->isAuthenticated($input)) {
            return $this->error($input, [
                'token' => 'No token found for this provider',
            ]);
        }

        $provider = $input['provider'];
        $token    = $this->session->get('tokens')[$provider];

        try {
            $owner = $this->provider->getResourceOwner($token);
        } catch (IdentityProviderException $e) {
            if ($e->getCode() === 401) {
                $this->storeToken($provider, null);

                return $this->error($input, [
                    'token' => 'Token for this provider is invalid, token removed',
                ]);
            }

            return $this->error($input, [
                'response' => $e->getResponseBody(),
            ]);
        }

        // Collect output
        $id      = $owner->getId();
        $details = $owner->toArray();

        return $this->payload()
            ->withStatus(Payload::STATUS_OK)
            ->withOutput(compact('provider', 'id', 'details') + [
                'template' => 'user',
            ]);
    }
}
