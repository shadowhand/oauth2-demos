<?php

namespace League\OAuth2\Client\Demo\Domain;

use Equip\Adr\DomainInterface;
use Equip\Payload;

abstract class AbstractDomain implements DomainInterface
{
    protected function payload()
    {
        return new Payload;
    }

    protected function error(array $input, array $messages = [])
    {
        return $this->payload()
            ->withStatus(Payload::STATUS_BAD_REQUEST)
            ->withInput($input)
            ->withMessages($messages)
            ->withOutput([
                'template' => 'error',
            ]);
    }
}
