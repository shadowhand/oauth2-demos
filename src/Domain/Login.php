<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\Payload;

class Login extends AbstractProviderDomain
{
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
        if (!$this->isOkay($input)) {
            return $this->error($input);
        }

        if (!$this->isValidState($input)) {
            return $this->error($input, [
                'error' => 'Invalid state detected',
            ]);
        }

        $provider = $input['provider'];
        $code     = $input['code'];
        $token    = $this->provider->getAccessToken('authorization_code', compact('code'));

        // Store the token to later actions
        $this->storeToken($provider, $token);

        return $this->payload()
            ->withStatus(Payload::STATUS_FOUND)
            ->withMessages([
                'redirect' => '/user/' . $provider,
            ]);
    }
}
