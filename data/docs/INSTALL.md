jul6art/symfony-skeleton
==
Base sf4 admin project
-

[![Build Status](https://jenkins.vsweb.be/buildStatus/icon?job=Symfony+skeleton)](https://jenkins.vsweb.be/job/Symfony%20skeleton/)

### Functionalities

> Every functionality can be disabled from the database or from 

    services.yml -> parameters -> %available_functionalities%

### Breadcrumb

> You can disabled the breadcrumb for a specific page

```twig
{% set breadcrumb = false %}
```

### [Forms](/data/docs/FORMS.md)

### [Audit](/data/docs/AUDIT.md)

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

&copy; 2019 [VsWeb](https://vsweb.be)