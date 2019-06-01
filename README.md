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

### Breadcrumb

> You can disabled the breadcrumb for a specific page

    {% set breadcrumb = false %}


### Forms

> You can override the form theme when extending the layout_form.html.twig

    {% set custom_form_theme = 'CUSTOM_layout_form.html.twig' %}

### Audits

> To acctivate audit for an entity you need  to

    - enable in configuration
    - make the voter retur true
    
> Custom audit level (6chars max)

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

> This command removes audits older tha a year

    bin/console audit:clean --no-confirm

### Theme

> Default theme is set in services.yml then, in user profile page, every user can choose the theme he wants

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