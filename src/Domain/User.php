<?php

namespace League\OAuth2\Client\Demo\Domain;

use League\OAuth2\Client\Demo\Session;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

use Aura\Payload\Payload;
use Spark\Adr\DomainInterface;

class User implements DomainInterface
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

    private function isAuthenticated(array $input)
    {
        if (empty($input['provider']) || !$this->session->has('tokens')) {
            return false;
        }

        $provider = $input['provider'];
        $tokens   = $this->session->get('tokens');

        return !empty($tokens[$provider]);
    }

    public function __invoke(array $input)
    {
        $payload = new Payload;

        if (!$this->isAuthenticated($input)) {
            return $payload
                ->setExtras([
                    'template' => 'error',
                ])
                ->setStatus(Payload::NOT_VALID)
                ->setInput($input)
                ->setMessages([
                    'token' => 'No token found for this provider',
                ]);
        }

        $provider = $input['provider'];
        $token    = $this->session->get('tokens')[$provider];

        try {
            $owner = $this->provider->getResourceOwner($token);
        } catch (IdentityProviderException $e) {
            if ($e->getCode() === 401) {
                $this->session->merge('tokens', [$provider => null]);
                return $payload
                    ->setExtras([
                        'template' => 'error',
                    ])
                    ->setStatus(Payload::NOT_AUTHENTICATED)
                    ->setInput($input)
                    ->setMessages([
                        'token' => 'Token for this provider is invalid, token removed',
                    ]);
            }

            return $payload
                ->setExtras([
                    'template' => 'error',
                ])
                ->setStatus(Payload::ERROR)
                ->setOutput($e->getResponseBody());
        }

        // Collect output
        $id      = $owner->getId();
        $details = $owner->toArray();

        return $payload
            ->setExtras([
                'template' => 'user',
            ])
            ->setStatus(Payload::SUCCESS)
            ->setOutput(compact('provider', 'id', 'details'));
    }
}
