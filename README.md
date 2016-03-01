# Mail statistics

Simple endpoint for sending emails and receiving data back in association with Mailgun

### Version

This packages uses features from Laravel 5.2 so you will need 5.2 if you want to use this package

currently: dev

### Installation

```
composer require bitsoflove/mailstats
```

#### Add the dependencies

Add the service providers to config/app.php

```
BitsOfLove\MailStats\MailStatsProvider::class,
Bogardo\Mailgun\MailgunServiceProvider::class,
```

and register the Mailgun Facade

```
'Mailgun' => Bogardo\Mailgun\Facades\Mailgun::class,
```

### Usage

Indicate to your website you would like to use the Mailgun service for sending emails in .env
```
MAIL_DRIVER=mailgun
MAILGUN_DOMAIN=
MAILGUN_SECRET=
```

The domain and secret can be found on https://mailgun.com/app in your specific domain settings pane.

***The MAILGUN_DOMAIN key is not the full domain, you only need what's after /v3/ eg. https://api.mailgun.net/v3/yourdomain.here the domain is yourdomain.here***

### Todos

 - Add tests
 - Add extended documention
 - Add usage example

License
----

MIT

**Free Software, Hell Yeah!**