<?php

declare(strict_types=1);

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

namespace Flowmailer\Symfony\Mailer\Tests\Transport;

use Flowmailer\API\Options;
use Flowmailer\Symfony\Mailer\Transport\FlowmailerApiTransport;
use Flowmailer\Symfony\Mailer\Transport\FlowmailerTransportFactory;
use Symfony\Component\Mailer\Test\TransportFactoryTestCase;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;

class FlowmailerTransportFactoryTest extends TransportFactoryTestCase
{
    public function getFactory(): TransportFactoryInterface
    {
        return new FlowmailerTransportFactory($this->getDispatcher(), $this->getClient(), $this->getLogger());
    }

    public function supportsProvider(): iterable
    {
        yield [
            new Dsn('flowmailer+api', 'default'),
            true,
        ];

        yield [
            new Dsn('flowmailer', 'default'),
            true,
        ];

        yield [
            new Dsn('flowmailer+api', 'example.com'),
            true,
        ];
    }

    public function createProvider(): iterable
    {
        $client     = $this->getClient();
        $dispatcher = $this->getDispatcher();
        $logger     = $this->getLogger();

        yield [
            new Dsn('flowmailer+api', 'default', self::USER, self::PASSWORD, null, ['account_id' => '123']),
            new FlowmailerApiTransport(new Options([
                'client_id'     => self::USER,
                'client_secret' => self::PASSWORD,
                'account_id'    => '123',
            ]), $client, $dispatcher, $logger),
        ];

        yield [
            new Dsn('flowmailer+api', 'default', self::USER, self::PASSWORD, null, ['account_id' => '123']),
            new FlowmailerApiTransport(new Options([
                'host'          => 'flowmailer.net',
                'client_id'     => self::USER,
                'client_secret' => self::PASSWORD,
                'account_id'    => '123',
            ]), $client, $dispatcher, $logger),
        ];

        yield [
            new Dsn('flowmailer+api', 'example.com', self::USER, self::PASSWORD, null, ['account_id' => '123']),
            (new FlowmailerApiTransport(new Options([
                'host'          => 'example.com',
                'client_id'     => self::USER,
                'client_secret' => self::PASSWORD,
                'account_id'    => '123',
            ]), $client, $dispatcher, $logger)),
        ];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield [
            new Dsn('flowmailer+foo', 'default', self::USER, self::PASSWORD),
            'The "flowmailer+foo" scheme is not supported; supported schemes for mailer "flowmailer" are: "flowmailer", "flowmailer+api".',
        ];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield [new Dsn('flowmailer+api', 'default', self::USER)];

        yield [new Dsn('flowmailer+api', 'default', null, self::PASSWORD)];
    }
}
