jul6art/symfony-skeleton
==
Base sf4 admin project
-

[![Build Status](https://jenkins.vsweb.be/buildStatus/icon?job=Symfony+skeleton)](https://jenkins.vsweb.be/job/Symfony%20skeleton/)

### Forms

> Types

    A lot of basic types are overrided in src/Forms/Type to include new options listed after this
    
    addon_left
    addon_right
    alert
    alert_class
    button_left
    button_left_class
    button_right
    button_right_class
    exploded
    mask
    no_float
    no_line
    pattern
    tooltip
    
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

&copy; 2019 [VsWeb](https://vsweb.be)