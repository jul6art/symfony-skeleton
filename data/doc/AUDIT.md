<p align="center">
    <a href="https://devinthehood.com"><img src="https://github.com/jul6art/symfony-skeleton/tree/master/assets/img/devinthehood.png?raw=true" alt="logo VsWeb"></a>
</p>

<p align="center">
    <a href="https://jenkins.vsweb.be/job/Symfony%20skeleton/" target="_blank"><img src="https://jenkins.vsweb.be/buildStatus/icon?job=Symfony+skeleton" alt="Build Status"></a>
    <a href="https://github.com/jul6art/symfony-skeleton/blob/master/data/report/coverage.svg" target="_blank"><img src="https://github.com/jul6art/symfony-skeleton/blob/master/data/report/coverage.svg" alt="Code Coverage"></a>
    <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
    <a href="https://github.com/jul6art/symfony-skeleton" target="_blank"><img src="https://img.shields.io/static/v1?label=stable&message=v1+coming+soon&color=orange" alt="Version"></a>
</p>

jul6art/symfony-skeleton
========================
Base sf4 admin project
----------------------


Activate audit for an entity
----------------------------

* enable in configuration
* make the voter return true
    
Exclude tracked fields
----------------------

```twig
{{ render(controller('App\\Controller\\DefaultController::audit', {
    class: 'App\\Entity\\Test',
    exclude: ['updatedAt', 'updatedBy']
})) }}
```    

Custom audit level
------------------

The level name must have 6 characters max.

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

In audit translation domain

```yml
audit.actions.test01: 'Custom action from %planet% on element #%objectId%'
```

Remove audits older than a year
-------------------------------

```console
bin/console audit:clean --no-confirm
```

License
-------

The VsWeb Symfony Skeleton is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

&copy; 2019 [dev in the hood](https://devinthehood.com)