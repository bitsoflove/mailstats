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

#### Configuration on mailgun

If you want to use this package you'll have to setup mailgun webhooks for your domain, https://documentation.mailgun.com/api-webhooks.html. By default the webhook url is /mail-statistics (POST) route defined in package routes.php file.

#### Default available routes

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

#### Daily usage

Full payload example:

```javascript
        function action() {
            jQuery.ajax({
                url: "http://<yourdomain.com>/mail-send",
                type: "POST",
                data: {
                    to: {
                        name: "<recipient_name_>",
                        email: "<recipient_email@domain.com>"
                    },
                    from: {
                        name: "<sender_name_>",
                        email: "<sender_email@domain.com>"
                    },
                    project: "<project>",
                    subject: "<subject>",
                    messageData: {
                        view: "<the_view_you_would_like_to_use>",
                        variables: {
                            <list_of_variables_to_use_in_the_view>
                        },
                        view_namespace: <the_namespace_if_any>
                    }
                },
                success: function (result) {
                    <do_something_on_success>
                }
            })
        }
```

Minimum required payload example:

If you've entered recipient and/or from information on your defined project. The package will replace them in the request. If the view_namespace is missing the default will be used "mail-stats", if you would like to use no namespace enter an empty string.

```javascript
        function action() {
            jQuery.ajax({
                url: "http://<yourdomain.com>/mail-send",
                type: "POST",
                data: {
                    project: "<project>",
                    subject: "<subject>",
                    messageData: {
                        view: "<the_view_you_would_like_to_use>",
                        variables: {
                            <list_of_variables_to_use_in_the_view>
                        },
                    }
                },
                success: function (result) {
                    <do_something_on_success>
                }
            })
        }
```

### Todos

 - Add extended documention
 - Add usage example
 - Add JWT security (v2)

License
----

MIT

**Free Software, Hell Yeah!**