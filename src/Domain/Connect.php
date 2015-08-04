<?php

namespace League\OAuth2\Client\Demo\Domain;

use League\OAuth2\Client\Demo\Session;
use League\OAuth2\Client\Provider\AbstractProvider;

use Aura\Payload\Payload;
use Spark\Adr\DomainInterface;

class Connect implements DomainInterface
{
    private $provider;
    private $session;

    public function __construct(
        AbstractProvider $provider,
        Session          $session
    ) {
        $this->provider = $provider;
        $this->session  = $session;
    }

    public function __invoke(array $input)
    {
        $url = $this->provider->getAuthorizationUrl();
        $this->session->set('state', $this->provider->getState());

        return (new Payload)
            ->setStatus(Payload::PROCESSING)
            ->setOutput($url);
    }
}
