<?php

declare(strict_types=1);

/*
 * This file is part of the Flowmailer Mailer Bridge for Symfony package.
 * (c) Flowmailer.
 */

namespace Flowmailer\Symfony\Mailer\Transport;

use Flowmailer\API\Collection\AttachmentCollection;
use Flowmailer\API\Flowmailer;
use Flowmailer\API\Model\Attachment;
use Flowmailer\API\Model\SubmitMessage;
use Flowmailer\API\Options;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
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
        Options $options,
        HttpClientInterface $client = null,
        EventDispatcherInterface $dispatcher = null,
        LoggerInterface $logger = null
    ) {
        $this->options    = $options;
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
            parse_url($this->options->getBaseUrl(), PHP_URL_HOST),
            $this->options->getAccountId()
        );
    }

    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope): ResponseInterface
    {
        $attachments = new AttachmentCollection();

        foreach ($email->getAttachments() as $attachment) {
            $headers = $attachment->getPreparedHeaders();

            $attachments->add(
                (new Attachment())
                    ->setContent(base64_encode($attachment->getBody()))
                    ->setContentId($attachment->getContentId())
                    ->setFilename($headers->getHeaderParameter('Content-Disposition', 'filename'))
                    ->setContentType($headers->getHeaderBody('Content-Type'))
                    ->setDisposition($headers->getHeaderBody('Content-Disposition'))
            );
        }

        $sender     = $envelope->getSender()->toString();
        $recipients = $this->stringifyAddresses($this->getRecipients($email, $envelope));

        foreach ($recipients as $recipient) {
            $submitMessage = (new SubmitMessage())
                ->setRecipientAddress($recipient)
                ->setSenderAddress($sender)
                ->setMessageType('EMAIL')
                ->setSubject($email->getSubject())
                ->setHtml($email->getHtmlBody())
                ->setText($email->getTextBody())
                ->setAttachments($attachments)
            ;

            $request  = $this->flowmailer->createRequestForSubmitMessage($submitMessage);
            $response = $this->flowmailer->getResponse($request);
        }

        return new class($response) implements ResponseInterface {
            /**
             * @var PsrResponseInterface
             */
            private $response;

            public function __construct(PsrResponseInterface $response)
            {
                $this->response = $response;
            }

            public function getStatusCode(): int
            {
                return $this->response->getStatusCode();
            }

            public function getHeaders(bool $throw = true): array
            {
                return $this->response->getHeaders();
            }

            public function getContent(bool $throw = true): string
            {
                return '';
            }

            public function toArray(bool $throw = true): array
            {
                return [];
            }

            public function cancel(): void
            {
            }

            public function getInfo(string $type = null): mixed
            {
                return '';
            }
        };
    }
}
