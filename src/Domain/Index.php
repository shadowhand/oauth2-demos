<?php

namespace League\OAuth2\Client\Demo\Domain;

use League\OAuth2\Client\Demo\ProviderConfig;
use League\OAuth2\Client\Demo\Session;

use Aura\Payload\Payload;
use Spark\Adr\DomainInterface;

class Index implements DomainInterface
{
    private $config;
    private $session;

    public function __construct(
        ProviderConfig $config,
        Session        $session
    ) {
        $this->config  = $config;
        $this->session = $session;
    }

    private function providers()
    {
        $tokens    = $this->session->get('tokens', []);
        $providers = $this->config->providers();

        $connected = array_fill_keys(array_keys(array_filter($tokens)), true);
        $available = array_fill_keys($providers, false);

        return array_replace($available, $connected);
    }

    public function __invoke(array $input)
    {
        $providers = $this->providers();

        return (new Payload)
            ->setStatus(Payload::SUCCESS)
            ->setExtras(['template' => 'index'])
            ->setOutput(compact('providers'));
    }
}
