<?php

declare(strict_types=1);

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

namespace Flowmailer\Symfony\Mailer\Transport;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

/**
 * @author Reyo Stallenberg <support@flowmailer.com>
 */
final class FlowmailerTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        $scheme    = $dsn->getScheme();

        $user      = $this->getUser($dsn);
        $password  = $this->getPassword($dsn);
        $accountId = $dsn->getOption('account_id');

        $host = 'default' === $dsn->getHost() ? null : $dsn->getHost();
        $port = $dsn->getPort();

        if ('flowmailer+api' === $scheme || 'flowmailer' === $scheme) {
            return new FlowmailerApiTransport($dsn, $this->client, $this->dispatcher, $this->logger);
        }

        throw new UnsupportedSchemeException($dsn, 'flowmailer', $this->getSupportedSchemes());
    }

    protected function getSupportedSchemes(): array
    {
        return ['flowmailer', 'flowmailer+api'];
    }
}
