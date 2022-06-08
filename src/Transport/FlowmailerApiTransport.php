<?php

declare(strict_types=1);

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

namespace Flowmailer\Symfony\Mailer\Transport;

use Flowmailer\API\Flowmailer;
use Flowmailer\API\Options;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractApiTransport;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @author Reyo Stallenberg <support@flowmailer.com>
 */
final class FlowmailerApiTransport extends AbstractApiTransport
{
    private Options $options;
    private Flowmailer $flowmailer;

    public function __construct(
        string $accountId,
        string $clientId,
        string $clientSecret,
        HttpClientInterface $client = null,
        EventDispatcherInterface $dispatcher = null,
        LoggerInterface $logger = null
    ) {
        $this->options = new Options(
            [
                'account_id'    => $accountId,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
            ]
        );

        $this->flowmailer = new Flowmailer(
            $this->options,
            $logger,
            null,
            new HttplugClient($client)
        );

        parent::__construct($client, $dispatcher, $logger);
    }

    public function __toString(): string
    {
        return sprintf(
            'flowmailer+api://%s?account_id=%s',
            $this->options->getBaseUrl(),
            $this->options->getAccountId()
        );
    }

    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope): ResponseInterface
    {
        // $this->flowmailer->submitMessage(...);
        // To be implemented
    }
}
