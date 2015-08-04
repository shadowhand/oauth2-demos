<?php

namespace League\OAuth2\Client\Demo\Domain;

use League\OAuth2\Client\Demo\Session;
use League\OAuth2\Client\Provider\AbstractProvider;

use Aura\Payload\Payload;
use Spark\Adr\DomainInterface;

class Login implements DomainInterface
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

    private function isOkay(array $input)
    {
        return empty($input['error'])
            && !empty($input['code']);
    }

    private function isValidState(array $input)
    {
        return !empty($input['state'])
            && $this->session->has('state')
            && $this->session->get('state') === $input['state'];
    }

    public function __invoke(array $input)
    {
        $payload = new Payload;

        if (!$this->isOkay($input)) {
            return $payload
                ->setExtras([
                    'template' => 'error',
                ])
                ->setStatus(Payload::NOT_AUTHENTICATED)
                ->setInput($input);
        }

        if (!$this->isValidState($input)) {
            return $payload
                ->setExtras([
                    'template' => 'error',
                ])
                ->setStatus(Payload::NOT_VALID)
                ->setInput($input)
                ->setMessages([
                    'error' => 'Invalid state detected',
                ]);
        }

        $provider = $input['provider'];
        $code     = $input['code'];
        $token    = $this->provider->getAccessToken('authorization_code', compact('code'));

        // Store the token to later actions
        $this->session->merge('tokens', [$provider => $token]);

        return $payload
            ->setStatus(Payload::AUTHENTICATED)
            ->setOutput(compact('provider'));
    }
}
