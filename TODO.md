### V1 skeleton

base theme

    https://github.com/gurayyarar/AdminBSBMaterialDesign
    
optimisations
    
    concatenation: double quotes comme ceci: "il y a une $a variable"   "je suis con{$act}caténée"
    pas de switch cases
    pas de is_null()
    pas de isset($a) mais $a ?? $b
    pas de backslash avant DateTime ou DateInterval mais bien les importer (use statement)
    backslash avant les fonctions: in_array, count, strlen, strval, gettype, get_class, call_user_func, array_slice, is_{type}

bugs

    js require et imports de quelques scripts (voir script-loader ????)

URGENT

    TESTER HELP ET ALERT AVEC TOUS LES TYPES
    
    améliorer js
        javascript dynamic {%- -%}
        ne plus traduire en attribut data
    optimisations html - js - php
        https://www.htmhell.dev/
    
    javascript edit in place
        mettre en place partout
            pages details
            maintenance
            layout
            fos pages
            form inputs label
            
    versions vulnerabilities et php upgrades
    
    revoir gestion des groupes dans users list / création / édition
    usermanager: retirer $user->addGroup($this->groupManager->findOneByName(Group::GROUP_NAME_ADMIN));
    password min et max length ett password strength plugin
        
    tests translate et maintenance
    tests usercontroller impersonate action (chaque cas du voter)
    tests services
        https://symfony.com/doc/current/testing.html
        mockery mock??
    
    bugs js: range validator google.recaptcha datetimepicker ...
        
    tuto bienvenue
        fonctionalité
        https://clu3.github.io/bootstro.js/index.html#
        http://fortesinformatica.github.io/Sideshow/example.html
        
    messenger bundle au lieu de maillistener et là ou c'est adéquat (+ documentation)
        https://symfony.com/doc/current/messenger.html

datatables

    https://php.developpez.com/actu/84373/Exemple-simple-pour-integrer-dataTable-avec-symfony2-par-phpiste/
    https://github.com/stwe/DtBundleDemo10/blob/master/src/AppBundle/Controller/EntityAController.php
    
    blockui a sur la table avec couleur de fond pendant chaque appel ajax (implémenté, à vérifier)
    filtering and url and data-ajax
    voir options datatable-bundle
    tester cas pourris: concat, abstarcttable, count, ...
    navigation couleur
    hide search input si aucun champ searchable
    events not fired (exemple: refresh apres data-confirm success)
    page_length default dans les parameters à gérer
    not-sortable not-searchable (attention sort by convert to lower)
    formats de cellules (money, localizeddate, email, phone, image, ...)
    highlight
    colreorder
    export col to exclude (action)
    row-checkbox
    actions multiples
    row-select

Valider code

crud adaptations:

    demander prefix de routes (/admin par défaut)
    adapter dossier templates/crud
    
ecrire des tests

    faire monter le coverage au maximum (voir commandes hook_local.sh)

liip imagine

    upload images (logo, background, avatar) et fichiers 
    upload images (plugin) dans wysiwyg avec voter (pas dans edit in place mais bien dans wysiwyg)
    
option form par ajax  | voir doc bonnes pratiques ajax symfony

    https://symfonycasts.com/screencast/javascript/api-endpoint-post#play
    https://codepen.io/mbezhanov/pen/japOaJ
    https://stackoverflow.com/questions/30655649/sweet-alert-with-form-js-3-input
    https://github.com/sweetalert2/sweetalert2/issues/412

autres types: + leur validation + leurs traductions

    spinners (voir theme)
    selects et select2
    collection
    multiselect
    tags inputs
    
### host
    refaire une migration unique
    vérifier documentation

cron relaunch fixtures hourly
    
### V2 skeleton post projet

séparer en bundles

systeme de notification equivalent websocket (liste header + page  de notifs et badges)

    https://symfony.com/blog/symfony-gets-real-time-push-capabilities
    
lexik translation bundle

    page / form d ajout de traduction
    tests phpunit
    !! important réécrire la partie JS Angular
    javascript edit in place (utiliser route de l interface de trads ??)
        ckeditor-inline deja installé
        attention aux balises p

progressive web app:

    new version banner toastr
        https://medium.com/progressive-web-apps/pwa-create-a-new-update-available-notification-using-service-workers-18be9168d717
        https://github.com/iamshaunjp/pwa-tutorial
        
tools d admin: 

    params globaux 
        default left menu background picture
    backup db et fichiers (!! mysql !== pgsql)
    activation des options pour les users 
        (notifications temps réel)

react + instantsearch

    https://github.com/appbaseio/reactivesearch