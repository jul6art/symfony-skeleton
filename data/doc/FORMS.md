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

Types
-----

A lot of basic types are overrided in **src/Forms/Type** to include the following options:
    
* addon_left
* addon_right
* alert
* alert_class
* button_left
* button_left_class
* button_right
* button_right_class
* exploded
* mask
* no_float
* no_line
* pattern
* tooltip
    
Addons
------

```php
$builder
    ->add('test', TextType::class, [
        'addon_left' => '<i class="fa fa-calendar"></i>',
        'addon_right' => 'addon text tranlslated from translation domain',
    ])
;
```
    
Buttons
-------

```php
$builder
    ->add('test', TextType::class, [
        'button_left' => '<i class="fa fa-check-square"></i>',
        'button_right' => 'button text translated from translation_domain',
        'button_right_class' => 'bg-green test',
    ])
;
```
    
Data-mask and validation
------------------------

[InputMask](https://github.com/RobinHerbots/Inputmask) documentation

```php
$builder
    ->add('test', TextType::class, [
        'mask' => "'mask': '99-9999999'",
        'pattern' => '\d{2}[\-]\d{7}',
    ])
;
```
    
Alert
-----

```php
$builder
    ->add('test', TextType::class, [
        'alert' => 'alert text translated from translation_domain',
        'alert_class' => 'alert-warning', // optional
    ])
;
```
    
Help
----

```php
$builder
    ->add('test', TextType::class, [
        'help' => 'help text translated from translation_domain',
    ])
;
```
    
Tooltips
--------

```php
$builder
    ->add('test', TextType::class, [
        'tooltip' => 'tooltip text translated from translation_domain',
    ])
;
```

Override the form theme when extending the form layout
------------------------------------------------------

```twig
{% set custom_form_theme = 'CUSTOM_layout_form.html.twig' %}
```

License
-------

The VsWeb Symfony Skeleton is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

&copy; 2019 [dev in the hood](https://devinthehood.com)