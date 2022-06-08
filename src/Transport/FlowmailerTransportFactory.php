<?php

declare(strict_types=1);

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

namespace Flowmailer\Symfony\Mailer\Transport;

use Flowmailer\API\Options;
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
        $scheme = $dsn->getScheme();

        if ('flowmailer+api' === $scheme || 'flowmailer' === $scheme) {
            $options = [];
            foreach (['account_id', 'protocol', 'base_url',  'auth_base_url',  'oauth_scope'] as $key) {
                $options[$key] = $dsn->getOption($key, null);
            }
            $options['host']          = 'default' === $dsn->getHost() ? null : $dsn->getHost();
            $options['client_id']     = $this->getUser($dsn);
            $options['client_secret'] = $this->getPassword($dsn);

            return new FlowmailerApiTransport(new Options(array_filter($options)), $this->client, $this->dispatcher, $this->logger);
        }

        throw new UnsupportedSchemeException($dsn, 'flowmailer', $this->getSupportedSchemes());
    }

    protected function getSupportedSchemes(): array
    {
        return ['flowmailer', 'flowmailer+api'];
    }
}
