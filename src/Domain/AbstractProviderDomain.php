<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\SessionInterface;
use League\OAuth2\Client\Provider\AbstractProvider;

abstract class AbstractProviderDomain extends AbstractDomain
{
    protected $provider;
    protected $session;

    public function __construct(
        AbstractProvider $provider,
        SessionInterface $session
    ) {
        $this->provider = $provider;
        $this->session  = $session;
    }

    protected function storeToken($provider, $token)
    {
        $tokens = array_replace(
            $this->session->get('tokens', []),
            [
                $provider => $token,
            ]
        );

        $this->session->set('tokens', $tokens);
    }
}
