jul6art/symfony-skeleton
==
Base sf4 admin project
-

[![Build Status](https://jenkins.vsweb.be/buildStatus/icon?job=Symfony+skeleton)](https://jenkins.vsweb.be/job/Symfony%20skeleton/)
[![Coverage Status](https://coveralls.io/repos/github/jul6art/symfony-skeleton/badge.svg?branch=master)](https://coveralls.io/github/jul6art/symfony-skeleton?branch=master)

> This project is still in development so please keep calm

[LIVE DEMO (USER: admin, PASSWORD: vsweb)](https://symfony-skeleton.vsweb.be/)

[COMPLETE DOCUMENTATION](/data/docs/INSTALL.md)

### Install

```bash
git clone https://github.com/jul6art/symfony-skeleton.git
```

### Configure

```bash
nano .env
```

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

### Launch

```bash
sh hook_local.sh
php bin/console server:start
```

Then visit [http://127.0.0.1:8000](http://127.0.0.1:8000)

[COMPLETE DOCUMENTATION](/data/docs/INSTALL.md)

### Credits

    Bootstrap
    Materialize
    Datatables.net
    Gedmo
    Stof
    FriendsOfSymfony
    Basic theme: https://gurayyarar.github.io/AdminBSBMaterialDesign/

&copy; 2019 [VsWeb](https://vsweb.be)