<p align="center">
    <a href="https://devinthehood.com"><img src="https://github.com/jul6art/symfony-skeleton/blob/master/assets/img/devinthehood.png?raw=true" alt="logo dev in the hood"></a>
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

### Functionalities

* Audit
* Cache clearing
* Edit in place
* Confirmation animated modal
* Form watching
* Progressive web app
* Settings management
* Multilingual
* Theme choice
    

> :warning: Every functionality can be disabled 

from
* the database
* the **%available_functionalities%** parameter in **services.yaml**

Breadcrumb
----------

You can disable the breadcrumb for a specific page

```twig
{% set breadcrumb = false %}
```

[Forms](/data/doc/FORMS.md)
---------------------------

[Audit](/data/doc/AUDIT.md)
---------------------------

Commands
--------

Purge outdated sessions

```console
bin/console skeleton:sessions:purge
```

Consume queued messages

```console
bin/console skeleton:messages:consume
```

Theme
-----

Default theme is set in the setting sidebar, every user can choose the theme he wants

> :warning: You can deactivate this feature in configuration page

Crud
----

> :warning: Html files and controller are adapted to this current architecture but you must write some files manually

* Voter
* Manager
* Transformers
* Event
* EventListener

> :warning: **Audit**, **blameable** and **timestampable** must be set manually

> :warning: Translation keys are not set

You can edit controller and twig skeleton templates in /templates/crud

License
-------

The Symfony Skeleton is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

&copy; 2019 [dev in the hood](https://devinthehood.com)
