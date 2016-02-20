<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\Payload;
use Equip\SessionInterface;
use League\OAuth2\Client\Demo\ProviderConfig;

class Index extends AbstractDomain
{
    private $config;
    private $session;

    public function __construct(
        ProviderConfig $config,
        SessionInterface $session
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

        return $this->payload()
            ->withStatus(Payload::STATUS_OK)
            ->withOutput([
                'template' => 'index',
                'providers' => $providers,
            ]);
    }
}
