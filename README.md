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
