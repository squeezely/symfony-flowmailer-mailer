# Flowmailer Symfony Mailer Bridge

Provides Flowmailer integration for Symfony Mailer.

## Installation

```bash
composer require flowmailer/symfony-flowmailer-mailer
```

## Configuration

Obtain credentials from [Flowmailer credentials wizard](https://dashboard.flowmailer.net/setup/sources/credentialswizard.html)

Add the obtained credentials to the MAILER_DSN in the env file:

```dotenv
MAILER_DSN=flowmailer://CLIENT_ID:CLIENT_SECRET@api.flowmailer.net?account_id=1234
```

When using in a symfony project, you'll need to tag the transport factory as a 'mailer.transport_factory'.

```yaml
# config/services.yaml
services:
   # Other services

    Flowmailer\Symfony\Mailer\Transport\FlowmailerTransportFactory:
        parent: 'mailer.transport_factory.abstract'
        tags:
            - name: 'mailer.transport_factory'
```

Stand alone:
```php
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Flowmailer\Symfony\Mailer\Transport\FlowmailerTransportFactory;

$factory   = new FlowmailerTransportFactory();
$transport = new Transport([$factory]);
$mailer    = new Mailer($transport->fromString($dsnString));

$email = (new Email())
    ->from('example@example.com')
    ->to('example@example.com')
    ->subject('Test e-mail')
    ->text('This is the content of the email')
    ->html('<p>Test e-mail with inline image</p><img src="cid:inline-image">')
    ->attachFromPath('path/to/attachment.pdf')
    ->embedFromPath('path/to/inline-image.png', 'inline-image')
;

$mailer->send($email);
```