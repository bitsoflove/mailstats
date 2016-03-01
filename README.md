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

### Todos

 - Add tests
 - Add extended documention
 - Add usage example

License
----

MIT

**Free Software, Hell Yeah!**