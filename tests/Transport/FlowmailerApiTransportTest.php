<?php

declare(strict_types=1);

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

namespace Flowmailer\Symfony\Mailer\Tests\Transport;

use Flowmailer\API\Options;
use Flowmailer\Symfony\Mailer\Transport\FlowmailerApiTransport;
use PHPUnit\Framework\TestCase;

class FlowmailerApiTransportTest extends TestCase
{
    /**
     * @dataProvider getTransportData
     */
    public function testToString(FlowmailerApiTransport $transport, string $expected)
    {
        $this->assertSame($expected, (string) $transport);
    }

    public function getTransportData()
    {
        return [
            [
                new FlowmailerApiTransport(new Options([
                    'host'          => 'example.com',
                    'client_id'     => 'CLIENT_ID',
                    'client_secret' => 'CLIENT_SECRET',
                    'account_id'    => '123',
                ])),
                'flowmailer+api://api.example.com?account_id=123',
            ],
            [
                new FlowmailerApiTransport(new Options([
                    'base_url'      => 'https://example.com',
                    'client_id'     => 'CLIENT_ID',
                    'client_secret' => 'CLIENT_SECRET',
                    'account_id'    => '123',
                ])),
                'flowmailer+api://example.com?account_id=123',
            ],
            [
                new FlowmailerApiTransport(new Options([
                    'client_id'     => 'CLIENT_ID',
                    'client_secret' => 'CLIENT_SECRET',
                    'account_id'    => '123',
                ])),
                'flowmailer+api://api.flowmailer.net?account_id=123',
            ],
        ];
    }
}
