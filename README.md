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

    DatepickerType
    
> Data-mask and validation

https://github.com/RobinHerbots/Inputmask

```php
$builder
    ->add('test', TextType::class, [
        'attr' => [
            'data-inputmask' => "'mask': '99-9999999'",
            'pattern' => '\d{2}[\-]\d{7}',
        ],
    ])
;
```
    
> Alert


```php
$builder
    ->add('test', TextType::class, [
        'attr' => [
            'data-alert' => 'alert text translated from translation_domain',
            'data-alert-class' => 'alert-warning', // or bg-orange
        ],
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
        'attr' => [
            'data-toggle' => 'tooltip',
            'data-original-title' => 'Untranslated tooltip text',
        ],
    ])
;
```

> You can override the form theme when extending the layout_form.html.twig

    {% set custom_form_theme = 'CUSTOM_layout_form.html.twig' %}

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