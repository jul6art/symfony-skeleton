jul6art/symfony-skeleton
========================
Base sf4 admin project
----------------------

<p align="center">
    <a href="https://jenkins.vsweb.be/job/Symfony%20skeleton/" target="_blank"><img src="https://jenkins.vsweb.be/buildStatus/icon?job=Symfony+skeleton" alt="Build Status"></a>
    <a href="https://github.com/jul6art/symfony-skeleton/blob/master/data/report/coverage.svg" target="_blank"><img src="https://github.com/jul6art/symfony-skeleton/blob/master/data/report/coverage.svg" alt="Build Status"></a>
    <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
    <a href="https://github.com/jul6art/symfony-skeleton" target="_blank"><img src="https://img.shields.io/static/v1?label=stable&message=v1+coming+soon&color=orange" alt="Version"></a>
</p>

> Work in progress so keep calm

Live demo available [here](https://symfony-skeleton.vsweb.be) (**user**: admin, **password**: vsweb)

Requirements
------------

* php >= 7.1
* mysql (for postgresql, sqlite or something else, you will need to update doctrine configuration)
* composer
* yarn

Code Quality
------------

* phpcs
* phpcpd
* phplint
* twigcs
* symfony security checker

Installation
------------

```console
git clone https://github.com/jul6art/symfony-skeleton.git
```


Then edit the root **.env** file

```console
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=2f64f0b539e90a15cd03d984d7bc4d56
#TRUSTED_PROXIES=127.0.0.1,127.0.0.2
#TRUSTED_HOSTS='^localhost|example\.com$'
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# Configure your db driver and server_version in config/packages/doctrine.yaml
DATABASE_URL=mysql://root:@127.0.0.1:3306/symfony_skeleton
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306
DATABASE_NAME=symfony_skeleton
DATABASE_USER=root
DATABASE_PASSWORD=
###< doctrine/doctrine-bundle ###

###> symfony/swiftmailer-bundle ###
# For Gmail as a transport, use: "gmail://username:password@localhost"
# For a generic SMTP server, use: "smtp://localhost:25?encryption=&auth_mode="
# Delivery is disabled by default via "null://localhost"
MAILER_URL=smtp://localhost:1025
MAILER_DEBUG_ADDRESS=
MAILER_FROM_ADDRESS=
MAILER_FROM_NAME='Symfony Skeleton'
###< symfony/swiftmailer-bundle ###

###> google/recaptcha ###
GOOGLE_RECAPTCHA_SITE_KEY=
GOOGLE_RECAPTCHA_SECRET=
###< google/recaptcha ###

###> domain ###
# Add your subdomains here. ex: admin.symfony-skeleton.localhost
# So you can select routes or controllers depending on the host
HTTP_PROTOCOL=http://
CURRENT_DOMAIN=symfony-skeleton.localhost
###< domain ###

###> symfony/messenger ###
MESSENGER_TRANSPORT_DSN=doctrine://default
###< symfony/messenger ###
```

Documentation
------------

* read more about [installation and usage](/data/doc/INSTALL.md)
* optional documentation about [forms](/data/doc/FORMS.md) or [audit](/data/doc/AUDITS.md)
* visit the [demo application](https://symfony-skeleton.vsweb.be) (**user**: admin, **password**: vsweb)

Testing
-------

```console
sh hook_local.sh
./vendor/bin/simple-phpunit
```

Start server
------------

```console
sh hook_local.sh
bin/console server:run
```

Then visit [http://127.0.0.1:8000](http://127.0.0.1:8000)

License
-------

The VsWeb Symfony Skeleton is open-sourced software licensed under the MIT license.

&copy; 2019 [VsWeb](https://vsweb.be)