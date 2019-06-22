jul6art/symfony-skeleton
==
Base sf4 admin project
-

> This project is still in development so please keep calm

[&#9758; DEMO](https://symfony-skeleton.vsweb.be/)

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

### Functionalities

> Every functionality can be disabled from the database or from 

    services.yml -> parameters -> %available_functionalities%

### Breadcrumb

> You can disabled the breadcrumb for a specific page

    {% set breadcrumb = false %}


### Forms

> Types

    A lot of basic types are overrided in src/Forms/Type to include new options listed after this
    
> Addons


```php
$builder
    ->add('test', TextType::class, [
        'addon_left' => '<i class="fa fa-calendar"></i>',
        'addon_right' => 'addon text tranlslated from translation domain',
    ])
;
```
    
> Buttons


```php
$builder
    ->add('test', TextType::class, [
        'button_left' => '<i class="fa fa-check-square"></i>',
        'button_right' => 'button text translated from translation_domain',
        'button_right_class' => 'bg-green test',
    ])
;
```
    
> Data-mask and validation

https://github.com/RobinHerbots/Inputmask

```php
$builder
    ->add('test', TextType::class, [
        'mask' => "'mask': '99-9999999'",
        'pattern' => '\d{2}[\-]\d{7}',
    ])
;
```
    
> Alert


```php
$builder
    ->add('test', TextType::class, [
        'alert' => 'alert text translated from translation_domain',
        'alert_class' => 'alert-warning', // optional
    ])
;
```
    
> Help

```php
$builder
    ->add('test', TextType::class, [
        'help' => 'help text translated from translation_domain',
    ])
;
```
    
> Tooltips

```php
$builder
    ->add('test', TextType::class, [
        'tooltip' => 'tooltip text translated from translation_domain',
    ])
;
```

> You can override the form theme when extending the layout_form.html.twig

    {% set custom_form_theme = 'CUSTOM_layout_form.html.twig' %}

### Selectors (classes to add to disable for specific item)

Autosize

    textarea:not(.no_autosize)
    
Jquery validate

    form:not(.no_validate)
    
Jquery areYouSure

    form:not(.no_watch)
    
Date and time Pickers

    [data-provide="datepicker"]
    [data-provide="timepicker"]
    [data-provide="datetimepicker"]

### Audits

> To activate audit for an entity you need to

    - enable in configuration
    - make the voter return true
    
> Exclude tracked fields on entity edit audit  (in twig  files)

```php
{{ render(controller('App\\Controller\\DefaultController::audit', {
    class: 'App\\Entity\\Test',
    exclude: ['updatedAt', 'updatedBy']
})) }}
```    
> Custom audit level (6chars max for the level name)

```php
use AuditManagerTrait;

public function onTestAdded(TestEvent $event)
{
    $this->getAuditManager()->audit('test01', $event->getTest(), [
        'id' => $event->getTest()->getId(),
        'planet' => 'Mars',
    ]);
}
```

in audit translation domain

    audit.actions.test01: 'Custom action from %planet% on element #%objectId%'

> This command removes audits older than a year

    bin/console audit:clean --no-confirm

### Theme

> Default theme is set in the setting sidebar, every user can choose the theme he wants

You can deactivate this feature in configuration page

### Crud

> Html files and controller are adapted to this current architecture but you must write some files manually

- Voter
- Manager
- Transformers
- Event
- EventListener

> Audit, blameable and timestapable must be set manually

> Translation keys are not set

> You can edit controller and twig skeleton files in /templates/crud

### Credits

    Bootstrap
    Materialize
    Datatables.net
    Gedmo
    Stof
    FriendsOfSymfony
    Basic theme: https://gurayyarar.github.io/AdminBSBMaterialDesign/

&copy; 2019 [VsWeb](https://vsweb.be)