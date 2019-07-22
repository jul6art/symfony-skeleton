jul6art/symfony-skeleton
==
Base sf4 admin project
-

[![Build Status](https://jenkins.vsweb.be/buildStatus/icon?job=Symfony+skeleton)](https://jenkins.vsweb.be/job/Symfony%20skeleton/)

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

&copy; 2019 [VsWeb](https://vsweb.be)