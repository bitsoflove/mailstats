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

Publish the migrations and views provided.

```sh
php artisan vendor:publish --provider="BitsOfLove\MailStats\MailStatsProvider"
```

And don't forget to migrate...

```
php artisan migrate
```

### Usage

#### Start
Indicate to your website you would like to use the Mailgun service for sending emails in .env
```
MAIL_DRIVER=mailgun
MAILGUN_DOMAIN=
MAILGUN_SECRET=
```

The domain and secret can be found on https://mailgun.com/app in your specific domain settings pane.

***The MAILGUN_DOMAIN key is not the full domain, you only need what's after /v3/ eg. https://api.mailgun.net/v3/yourdomain.here the domain is yourdomain.here***

#### Daily

The package provides 2 basic Entities: Project and MailStatistic.

For Project there is a simple CRUD workflow accessible via:

METHOD    | route                                     | name                       | description
----------|-------------------------------------------|----------------------------|-------
GET  | projects                                  | projects.index | List of all the projects
GET  | projects/create                           | projects.create | Create a new project
POST      | projects                                  | projects.store | Create the project in the database
GET  | projects/{projects}/edit                  | projects.edit | Update an existing project
PUT/PATCH | projects/{projects}                       | projects.update | Update the project in the database
GET  | projects/{projects}/delete                | projects.delete | Delete an existing project (confirmation needed)
DELETE    | projects/{projects}                       | projects.destroy | Actually delete the given project

For MailStatistic you get:

METHOD    | route                                     | name                       | description
----------|-------------------------------------------|----------------------------|-------
POST      | mail-send                                 || Endpoint to post a request to to send an email via JavaScript
POST      | mail-statistics                           | | Endpoint to post Mailgun webhooks to, these are saved in the database
GET  | mail-statistics                           |    | List all MailStatistic entities (paginated)
GET  | mail-statistics/{projectSlug}             | mail-stats-per-project| Show all MailStatic information for the requested project
GET  | mail-statistics/{projectSlug}/{messageId} | mail-stats-per-message-id | Show the bundled information for the given message-id


### Todos

 - Add tests
 - Add extended documention
 - Add usage example
 - Add JWT security (v2)

License
----

MIT

**Free Software, Hell Yeah!**