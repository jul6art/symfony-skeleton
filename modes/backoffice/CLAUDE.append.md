
## Mode « backoffice »

Un back-office mono-locataire : thème maison, datatables au-dessus d'API Platform, rafraîchissement
Mercure, moteur de permissions ACL. La coquille et le socle de tableau viennent de deux bundles ;
ce dépôt porte ce qui lui appartient — ses entités, son catalogue de permissions, ses écrans.

### Ce qui vient d'où

| Ce que vous voyez | D'où ça vient | Ce que vous ne devez pas réécrire |
| --- | --- | --- |
| Le layout, la barre latérale, le menu de compte, les toasts | `jul6art/admin-bundle` | `@Admin/layout.html.twig`, ses blocs sont documentés en tête |
| Les pages de connexion / inscription / mot de passe oublié | `jul6art/admin-bundle` | logo et nom viennent d'`admin.branding` |
| L'écran d'apparence, les cinq colonnes `appearance_*` | `jul6art/admin-bundle` | `AppearancePreferencesTrait`, `AppearanceType` |
| Le thème : jetons d'accent, densité, `.panel`, `.btn-*` | `jul6art/admin-bundle` | `assets/styles/*` du bundle, importés par `assets/styles/app.css` |
| Les tableaux : pagination, tri, filtres, actions de masse, temps réel | `jul6art/datatable-bundle` | le contrôleur Stimulus, `AbstractDataTableConfigProvider` |
| Les filtres API Platform (`OrSearchFilter`, tri insensible à la casse) | `jul6art/api-bundle` | |
| Les types de formulaire (e-mail, mot de passe, montant, IBAN…) | `jul6art/ui-bundle` | |
| Un choix d'entité adossé à une table qui grossit (clients, sites, comptes…) | `jul6art/ui-bundle` | `AutocompleteEntityType` : la page ne porte que la valeur courante, le `query_builder` reste la borne de ce qui est accepté |
| Le moteur de permissions, `#[CheckPermission]`, le voter | `jul6art/acl-bundle` | |
| Mercure : publication, jetons, canaux | `jul6art/push-bundle` | |
| Import/export CSV et XLSX, en flux | `jul6art/dataflow-bundle` | `TabularResponseFactory`, `CsvWriter`, `ImportRunner`, `HeaderInspector` — cf. `src/Import/UserRowMapper.php` et `src/Controller/Admin/UserImportController.php` pour l'exemple livré |
| Repository, voter, contrôleur de base, traits d'entité, purge | `jul6art/core-bundle` | |

**Avant d'écrire une classe, vérifiez qu'un de ces bundles ne la fournit pas déjà.** Réimplémenter
ce qu'ils portent est l'erreur la plus coûteuse de cet écosystème, parce qu'elle ne se voit pas.

### Créer un écran de liste

1. Une classe `App\DataTable\<Entité>DataTableConfigProvider` étendant
   `Jul6Art\DatatableBundle\DataTable\AbstractDataTableConfigProvider`. Utilisez les aides
   (`column()`, `staticFilter()`, `apiFilter()`, `linkAction()`, `bulkDeleteAction()`) : chaque
   libellé passe par le traducteur, et c'est le seul garde-fou contre un en-tête en clé brute.
2. Un `#[ApiResource]` sur l'entité, avec `GetCollection` gardé par un code de permission, plus les
   filtres que les colonnes supposent, déclarés par `#[QueryParameter]` (jamais `#[ApiFilter]`,
   déprécié et refusé par `api-bundle` 2.x) : le gabarit `order[:property]` d'un
   `CaseInsensitiveOrderFilter` pour chaque `sortField`, `search` et un `OrSearchFilter` pour la
   recherche globale — `src/Entity/User.php` en est le modèle.
3. Une action de contrôleur qui passe `columns_config`, `filters_config` et `actions_config`.
4. Un gabarit qui inclut `_csrf.html.twig`, DANS la balise `<table>`.
5. Une route POST par action de ligne, avec son `#[IsGranted]` et son jeton `datatable_action`.

Les cinq, ou la table est cassée d'une manière qui ne lève pas : `sortField` manquant → le tri est
ignoré côté serveur ; `_csrf` oublié → les actions répondent 419 ; une clé absente du domaine
`javascript` → la barre de filtres affiche des clés.

### Exporter ou importer un tableau

`jul6art/dataflow-bundle` porte le moteur — écrivains en flux, lecteur CSV, correspondance de
colonnes, résolution de doublons PAR LOT — et ce dépôt l'exemplifie sur les comptes
(`UserController::export()` + `UserCsvExporter`, `UserImportController` + `UserImportService`,
`UserRowMapper`, `UserDuplicateResolver`). Le motif tient en trois pièces, quelle que soit
l'entité :

**Un export**, en une méthode :

```php
public function export(): Response
{
    return $this->responses->stream($this->csvWriter, MonExporteur::HEADER, $this->exporteur->rows(), 'nom-du-fichier');
}
```

⚠️ **`rows()` doit être un `Generator` construit sur `toIterable()` + `HYDRATE_SCALAR`**, jamais un
tableau : la mémoire d'un export dépend du NOMBRE de lignes traité, pas du plafond arbitraire qui
finirait par être atteint.

⚠️ **`HYDRATE_SCALAR` ne convertit pas chaque colonne pareil, et ça se VÉRIFIE, pas se devine.**
Une colonne JSON (`roles` sur `User`) revient en TEXTE encore encodé — `json_decode()` est à la
charge de l'exportateur. Une colonne booléenne revient en `bool` natif. Une colonne date/heure
revient déjà en chaîne au format `Y-m-d H:i:s`, pas en objet — l'appeler `->format()` lève une
`TypeError` à la première ligne. Cf. `UserCsvExporter` pour les trois cas de figure, tranchés par
une commande de débogage plutôt que supposés.

**Un import**, en deux routes — téléversement puis correspondance, jamais une seule requête :

1. Le fichier est déplacé dans un répertoire temporaire ; un jeton ALÉATOIRE, seul visible du
   navigateur, associe ce chemin à la session. Exposer le chemin réel au client permettrait une
   traversée (`/tmp/../etc/passwd`).
2. La correspondance vient du bundle (`@Dataflow/import/_mapper.html.twig`) ; le gabarit du projet
   ne pose que le formulaire autour — action, jeton CSRF, bouton.
3. `App\Import\<Entité>RowMapper` porte les champs obligatoires et les refus métier
   (`\DomainException`, une clé de traduction) ; `App\Import\<Entité>DuplicateResolver` porte ce qui
   fait que deux lignes désignent la même chose, résolu en UNE requête pour tout le lot.

⚠️ **Une politique de mot de passe n'a rien à faire dans un fichier.** `UserRowMapper` illustre la
règle : un compte importé reçoit un hachage ALÉATOIRE que personne ne connaît, jamais une valeur
lue dans une colonne — il s'active par « mot de passe oublié », le flux que ce mode livre déjà.

⚠️ **La garde de permission se répète sur CHAQUE méthode**, comme partout ailleurs dans ce mode :
`RouteAccessDecisionTest` (règle n°1) le vérifie par réflexion, méthode par méthode, jamais par
classe.

### Les préférences par compte : colonnes, ordre, vues enregistrées

Un sixième include, celui-là **optionnel**, ouvre le sélecteur de colonnes et les vues
enregistrées pour un tableau :

```twig
{{ include('@Datatable/datatable/_preferences.html.twig', { key: 'user' }) }}
```

La clé nomme un TABLEAU et pas une entité : deux écrans listant la même entité avec des colonnes
différentes sont deux clés. Motif accepté par la route : `[a-z0-9][a-z0-9_.-]{0,63}`. Sans
l'include, la table se rend exactement comme avant — pas de requête, pas de boutons.

Le bundle **interprète** les préférences (il borne, assainit, versionne) et sert `GET` / `PUT` /
`DELETE` sur `/admin/datatable/preferences/{key}` ; il ne stocke rien. Ce mode fournit la moitié
qui manque, et elle vient TOUTE de la brique « comptes » :
`App\Entity\DatatablePreference`, son repository, `App\DataTable\DatatablePreferenceStore`, l'alias
du port dans `config/services.yaml` et la route dans `config/routes/datatable.yaml`.

- **Sans l'alias, il n'y a pas d'endpoint** : `PreferenceControllerPass` retire le contrôleur du
  conteneur. Pas de panneau, pas d'erreur — le même sens de défaillance silencieuse que les deux
  alias ACL.
- **Le compte vient du jeton de sécurité**, jamais du payload : écrire les préférences d'un autre
  n'est pas « interdit », c'est irreprésentable. D'où l'absence de voter et de code de permission.
- **`write()` est un upsert.** L'endpoint est un `PUT` et le navigateur renvoie tout l'état à chaque
  sauvegarde : un `INSERT` aveugle heurte l'UNIQUE `(owner_id, datatable_key)` et sort en 500 au
  SECOND clic.
- **Le contenu est opaque** : colonne `text`, jamais `json`. Un type `json` ferait décoder Doctrine
  à la lecture et ré-encoder à l'écriture, donc les octets rendus ne seraient plus ceux reçus.

**Déclarer large, montrer étroit.** Une colonne que le lecteur peut masquer ne coûte rien à celui
qui n'en veut pas : la question n'est plus « mérite-t-elle la largeur ? » mais « quelqu'un
pourrait-il vouloir la voir ? ». `hidden: true` sur une aide de colonne la met dans le sélecteur et
hors du premier rendu — c'est ce que font `lastName` et `firstName` de la table des comptes. Trois
choses à savoir : le drapeau n'est honoré que si le tableau a l'include ci-dessus ; une colonne
ajoutée depuis la dernière sauvegarde d'un compte reprend ce défaut, donc livrer un lot de colonnes
masquées n'élargit l'écran de personne ; et c'est un défaut d'AFFICHAGE — une colonne masquée reste
sérialisée, donc jamais une raison d'ajouter un champ à un groupe `read`.

**Une colonne se déclare avec son tri et son filtre, ou pas du tout.** Un `sortField` sans l'entrée
correspondante dans l'`OrderFilter` envoie un `order[…]` que le serveur ignore : le clic ne fait
rien, et rien ne le signale. C'est la raison pour laquelle `roles` est un `readOnlyColumn` et
`lastName` un `column`.

### Les pièges de ce mode, appris ailleurs

- **`admin.base_template` désigne `templates/base.html.twig`.** Sans cette clé, une page
  d'administration étend la base du bundle et s'affiche **sans aucune feuille de style** — rien ne
  le signale.
- **`acl.multi_tenant: false` est vital ici.** Le moteur refuse par défaut toute vérification
  derrière `/api/` dont le locataire n'est pas résolu ; sans locataires, cela refuse tout à tout le
  monde sauf au super-admin, et le symptôme est une datatable vide.
- **L'identifiant Stimulus du tableau est déclaré deux fois** — par le CHEMIN de
  `assets/controllers/core/datatable_controller.js` et par `datatable.stimulus_identifier`. Les deux
  doivent dire `core--datatable`.
- **Ajouter un rendu de badge** = une entrée dans `datatable.status_maps` **et** une dans
  `assets/datatable/renderers.js`. Un nom inconnu du registre affiche la valeur brute.
- **Le catalogue du NAVIGATEUR est le domaine `javascript`**, et c'est le SEUL que
  `symfony/ux-translator` dépose dans le paquet JS (`core.js_translations.domain`). Toute clé lue
  par un `t()` JavaScript y vit : le socle du tableau, les cas des cartes de statuts, le tree
  `datatable.modal:` et les onze `datatable.dt.*` de DataTables.
  Le domaine `datatable` subsiste pour ce que le SERVEUR rend — ici la seule
  `datatable.col.organization`. **Le tri se fait clé par clé, jamais fichier par fichier.**
- ⚠️ **`javascript` est un domaine de TRANSPORT, pas de sujet.** Les autres domaines répondent à
  « de quoi parle ce libellé » ; celui-ci répond à « qui le lit ». Un vocabulaire d'énumération lu
  par un badge ET par une fiche y est DÉPLACÉ, jamais recopié — deux exemplaires divergent au
  premier changement.
- ⚠️ **Déplacer une clé oblige à reprendre TOUS ses appelants, y compris côté serveur.**
  `tests/Translation/JsTranslationTest.php` ne regarde que le domaine `javascript` : il ne verrait
  pas un gabarit resté sur `'datatable'`. C'est ce qui a sorti `datatable.quote_status.sent` en
  clair sur une fiche de `cereezer`, avec un garde vert.
- **Un code de permission mal orthographié OUVRE l'écran.** Le voter s'abstient sur ce qu'il ne sait
  pas lire, et une décision où tous les voters s'abstiennent vaut « accordé ».
- **`npm` est requis dans ce mode**, et seulement dans celui-là — voir ci-dessous.

### Les pièges du THÈME DE FORMULAIRE (appris à l'écran, 2026-08-23/24)

- **La classe d'un champ passe par les OPTIONS de `form_widget`, jamais par `form_widget_simple`.**
  Les `Custom*Type` d'`ui-bundle` rendent tous par `input_group_addon_widget`, qui écrit son PROPRE
  `<input>` et ne délègue pas. Une classe posée dans `form_widget_simple` n'atteint donc pas le
  champ : l'`<input>` sort sans attribut `class`, donc sans bordure ni fond — blanc sur blanc,
  invisible, HTML parfaitement valide, rien en console. C'est `templates/form/theme.html.twig` qui
  s'en charge, dans son bloc `form_control`.
- **Le conteneur compound du formulaire racine porte `form-grid`.** `form_widget(form)` rend un
  `<div id="…">` intermédiaire ; un `space-y-*` posé sur le `<form>` ne voit que ce div et le
  bouton, jamais les lignes. Sans ça, tous les libellés sont collés au champ précédent.
- **Un jeton de composant (`@layer components`) perd contre TOUTE utilitaire Tailwind posée à côté
  de lui.** `class="toggle-switch block w-fit"` a réduit seize interrupteurs à **0 px de large** —
  invisibles, non cliquables, gabarit valide. On centre par le PARENT (`text-center` sur la
  cellule), on n'écrase jamais le composant.
- **La largeur d'un écran se pose DANS le contenu, jamais par `{% block container_class %}`** : le
  conteneur du layout porte `mx-auto`, donc restreindre le bloc recentre toute la page.
- **Un rendu de datatable est CURRIFIÉ** : `(contexte) => (valeur) => html`. L'appeler à plat rend
  une fonction là où DataTables attend une chaîne, et l'exception avorte le `drawCallback` ENTIER —
  la colonne ET la ligne de filtres disparaissent, pour une parenthèse mal placée.
- **`BulkActionRunner::run()` NE FLUSHE PAS.** Il ouvre une transaction, applique l'action et
  commite ; le flush appartient à l'appelant. Sans lui, une route de masse répond 302, pose son
  flash de succès et n'écrit rien.
- **`SendEmailMessage` est routé en `sync` en dev et en test** (`config/packages/messenger.yaml`).
  Le routage `async` est juste en production, où un worker tourne ; en local la file se remplit et
  rien n'arrive jamais — et le jour où on la draine, elle livre des messages sérialisés des heures
  plus tôt, avec des jetons périmés.
- **Changer la langue demande un écouteur à la priorité 7 ET une rediffusion manuelle**
  (`src/EventListener/LocaleListener.php`) : le pare-feu remplit `TokenStorage` à 8,
  `LocaleAwareListener` diffuse la locale à 15 sans repasser. Poser `$request->setLocale()` seul
  écrit la colonne, écrit la session, et laisse l'écran dans la langue précédente.
- **La langue voyage dans le CORPS de la requête**, pas dans l'URL : c'est le contrat du
  contrôleur `ui--locale-switcher` d'`admin-bundle`. Une route `/locale/{locale}` répond 204 et ne
  change rien.

### Les fixtures font partie du mode

`doctrine:fixtures:load` **PURGE**. Tout ce sans quoi l'application ne démarre pas doit donc vivre
dans les fixtures et pas seulement dans une commande de seed : `UserFixtures` recrée le premier
administrateur, `RolePermissionFixtures` repose ce que `app:permissions:seed` avait posé. Sans la
seconde, un `make fixtures` laisse la base sans aucune permission et tous les écrans gardés
répondent 403.

**Un fichier de fixtures par classe d'entité**, jamais deux entités dans un même fichier.
`AppFixtures` reste vide : c'est un vestige du squelette, pas un fourre-tout.

### Pourquoi ce mode utilise Webpack Encore

Les autres modes du squelette n'ont pas de Node : Tailwind y passe par un binaire autonome et les
paquets par l'importmap. Celui-ci fait exception, en connaissance de cause.

Le socle de tableau repose sur DataTables 2, son greffon Responsive, jQuery et Select2 — un greffon
jQuery en UMD. C'est la pile exacte dont `datatable-bundle` a été extrait, et la seule éprouvée de
bout en bout aujourd'hui. Reproduire une chaîne éprouvée vaut mieux que préserver une propriété du
squelette au prix d'un socle qu'on n'a pas essayé.

La voie AssetMapper reste ouverte : elle demande de lever une inconnue — Select2 consommé par
`importmap:require` — et, si elle ne se lève pas, de remplacer Select2 par une autocomplétion sans
jQuery. Ce jour-là, `webpack.config.js`, `package.json` et `bundle-assets.js` disparaissent ; le
reste ne bouge pas.

### Le profileur de performance (développement)

`core-bundle` compte les requêtes SQL de chaque requête HTTP et les enregistre ;
`admin-bundle` en affiche le tableau de bord sur `/admin/performance`, et le menu de compte y
mène. Les trois pièces ne vivent qu'en développement : le drapeau
(`config/packages/performance.yaml`), les routes (`config/routes/performance.yaml`) et la clé
`admin.routes.performance` sont toutes sous `when@dev`.

C'est l'outil qui répond à la seule question qui décide de la vitesse d'une application Symfony :
**combien de requêtes cette route fait-elle, et ce nombre grandit-il avec les données ?** Le
panneau de la barre de debug donne la réponse page par page ; le tableau de bord la donne pour
toutes les routes visitées, triées par coût.

⚠️ Le préfixe de nom `admin_performance_` est un contrat entre les deux bundles : le core l'exclut
de sa collecte (`ignored_route_prefix`), sans quoi le tableau de bord mesurerait sa propre page.

### Six pièges de plus, tous trouvés en regardant les écrans (2026-08-24)

Aucun n'était visible dans une suite verte, et cinq naissaient de ce mode — donc de tout projet
qu'il génère.

- **Une clé de flash posée par un TERNAIRE échappe au scan.**
  `addFlash('success', $actif ? 'x.activated' : 'x.deactivated')` est la forme normale dès qu'une
  route bascule un statut, et un motif qui exige `', 'clé')` n'en capture aucune des deux :
  `FlashKeyTest` restait vert avec deux clés absentes du catalogue, affichées BRUTES dans le toast.
  Il capture désormais l'argument entier puis toutes les chaînes qu'il contient.
- **`$catalogue->has()` suit la chaîne de repli, `defines()` non.** Un test qui vérifie une clé
  avec `has()` sur la locale non-défaut la trouve dans `en` et passe au vert, pendant que l'écran
  français affiche l'anglais. Et vérifier la seule locale par défaut ne prouve rien de l'autre :
  `FlashKeyTest` boucle sur `BackofficeLocales::SUPPORTED` avec `defines()`.
- **Une carte de statuts se DÉCLARE, elle ne se transporte plus.** Le navigateur a le catalogue ;
  `datatable.status_maps` sert à dire au garde de traduction que ces clés sont vivantes, alors
  qu'aucun littéral du JavaScript ne les nomme — `badge()` les construit par interpolation. Deux
  surfaces vont ensemble : la carte et le rendu (`assets/datatable/renderers.js`), et le
  `labelPath` passé à `badge()` est le préfixe de la clé du CATALOGUE.
- **Le `var/translations/index.js` doit exister AVANT `npm run build`.** `assets/translator.js`
  l'importe, et `/var/` est gitignoré : `make assets` préchauffe d'abord. ⚠️ Et c'est bien
  `cache:warmup` qu'il faut, pas `ux:translator:warm-cache` seul — le warmer dépose les catalogues
  DÉJÀ CHARGÉS, donc sur un traducteur froid il écrit `export const messages = {};`, un fichier
  valide et un catalogue vide, sans rien lever.
- **Un `dateRangeFilter()` sans `DateFilter` sur l'entité ne filtre RIEN.** API Platform jette en
  silence un paramètre qu'aucun filtre ne réclame : la barre s'ouvre, se remplit, affiche
  « depuis le … » et ne retire aucune ligne (103 avant, 103 après, mesuré). `OrderFilter` ne
  remplace pas : il trie. `DateRangeFilterMappingTest` vérifie les deux sens — toute colonne date a
  son filtre, tout filtre a son mapping.
- **La suite tourne EN PARALLÈLE** (`./paratest.sh`, appelé par `make test`) : sur un projet de
  deux cents tests, neuf secondes au lieu de soixante-dix, donc un `make qa` complet en une
  dizaine. Rien à isoler entre les processus — la suite tourne sur SQLite en mémoire et chaque test
  crée son schéma par `SchemaTool`, donc chaque processus a déjà sa base. ⚠️ **Ne pas recopier la
  mécanique de bases modèles d'un projet PostgreSQL** : elle résout le partage d'un serveur de
  bases, problème que ce mode n'a pas. Le cache de test est réchauffé UNE fois avant les workers,
  sans quoi N processus compilent le conteneur dans le même dossier — course rare, silencieuse, et
  qui se manifeste par un rouge irreproductible. `make test-serial` garde le mono-thread : un test
  qui ne tombe qu'en parallèle dépend d'un état partagé entre processus, et c'est LUI qu'il faut
  corriger.
- **`column()` rend une colonne triable PAR DÉFAUT** (`sortField ?? $data`) : déclarer une colonne
  suffit à PROMETTRE un tri. Si l'entité ne réclame pas la propriété dans son `OrderFilter`, API
  Platform jette le paramètre en silence — l'en-tête s'allume, passe en « descendant », et l'ordre
  ne bouge pas. Une valeur calculée en PHP (`fullName`) n'est ordonnable que par le
  `ConcatOrderFilter` d'`api-bundle`. Figé par `SortFieldMappingTest` ; vérifié à l'écran en
  cliquant l'en-tête DEUX fois — l'ordre ascendant coïncide souvent avec l'ordre par défaut.
- **Une entité supprimable en douceur descend DEUX champs, pas un.** `deletedAt` est lu par
  `createdRow` du contrôleur Stimulus pour TEINTER la ligne ; `isDeleted` est lu par les
  `condition` des actions. Il manquait toujours l'un des deux sur le projet d'origine : sans
  `deletedAt`, une ligne supprimée s'affiche comme une ligne vivante ; sans `isDeleted`,
  « Restaurer » n'apparaît JAMAIS et « Supprimer » s'offre sur une ligne morte (une condition qui
  lit un champ absent vaut `undefined`, donc `!row.isDeleted` est toujours vrai). Figé par
  `SoftDeletableExposureTest`, qui DÉCOUVRE les entités au lieu de les lister.
  ⚠️ Et une colonne UNIQUE libérée à la suppression (`Strings::markDeleted()`) doit être RENDUE à
  la restauration (`Strings::restoreDeleted()`), après vérification qu'elle n'a pas été reprise :
  un compte restauré avec l'adresse `x@y_DELETED_1787…` est vivant, listé, et incapable de se
  connecter.
- **Un `<select>` de plusieurs centaines d'options ne se parcourt pas.** Le contrôleur
  `ui--select2` est enregistré par le squelette et n'est monté nulle part : le brancher par les
  `attr` du champ (`data-controller`, `data-ui--select2-url-value`,
  `data-ui--select2-search-key-value="search"`). ⚠️ Le `query_builder` RESTE — le Select2 borne ce
  qui est PROPOSÉ, le formulaire ce qui est ACCEPTÉ. N/A sur un `ChoiceType` en `expanded: true` :
  ce sont des cases à cocher, que le thème rend en interrupteurs.
- **Un `apiFilter()` promet DEUX correspondances, et chacune peut être fausse seule.** (1) Son
  `param` doit être filtrable sur la collection LISTÉE — un chemin qui traverse une relation
  s'écrit en entier (`site.customer`, jamais `customer`) ; (2) son `searchKey` doit être réclamé
  par la collection INTERROGÉE, et il vaut par DÉFAUT le nom du champ affiché (`?name=…`), que
  presque aucune ressource n'expose. Passer `searchKey: 'search'` pour viser un `OrSearchFilter`.
  Dans les deux cas la panne est silencieuse : la liste déroulante s'ouvre, se remplit, et ne se
  réduit JAMAIS à mesure qu'on tape ; la table affiche « filtre actif » et rend le même décompte.
  Invisible avec six options, inutilisable au-dessus de deux cents.
- **Un point dans un chemin de filtre ne prouve pas une relation.** `address.city` est un champ
  d'EMBEDDABLE : Doctrine le range dans la table du porteur et l'adresse directement. Le joindre
  lève `Association name expected` — une 500 sur une collection qui répondait parfaitement, jusqu'au
  jour où un écran s'est servi de sa recherche. Corrigé dans `api-bundle` ≥ 1.0.1 ; la leçon reste :
  un chemin pointé se vérifie sur les métadonnées, pas sur la forme de la chaîne.
- **Les dates d'un tableau s'affichent en `DD/MM/YYYY HH:mm`** (`dateOnly` : `DD/MM/YYYY`), dans
  toutes les langues — figé par `datatable-bundle` ≥ 1.4.0. Le format ne suit PAS la locale :
  `dateStyle: 'short'` rend `8/25/26` en anglais et `25/08/26` en français, mêmes chiffres, ordre
  inverse, et rien à l'écran ne dit lequel on lit. Un rendu maison qui reformaterait une date
  lui-même retomberait dans le défaut.
- **`ui-bundle` doit être dans le contenu scanné par Tailwind.** Il ne livre ni JS ni CSS, donc il
  n'a rien à faire dans `FRONT_BUNDLES` — mais son thème de formulaire est le seul endroit où
  passent les classes des `Custom*Type`. Sans lui, elles sont purgées, et le symptôme n'est pas une
  erreur : c'est une icône NOIRE au lieu de grise sur chaque champ à add-on. D'où `TEMPLATE_BUNDLES`
  dans `bundle-assets.js`, et une résolution qui teste la RACINE du paquet et non son `assets/` —
  un bundle sans `assets/` faisait sinon retomber tous ses chemins sur `../BUNDLES/`, présent chez
  le développeur et absent en CI.
- **Un pare-feu `stateless` vide le stockage de jetons à chaque requête.** Un test qui fait
  `loginUser()` puis DEUX appels à `/api/…` échoue sur le second en 401 « JWT Token not found », ce
  qui ressemble à un défaut d'autorisation. Signer chaque appel avec un JWT
  (`JWTTokenManagerInterface::create()`) — c'est aussi ce que fait la vraie table, via
  `window.jwtToken`.

Et une règle d'interface, figée par `ActionButtonTest` : **tout bouton d'action porte son icône et
sa variante de couleur**, les mêmes que la même action ailleurs — enregistrer `btn-primary` +
`fa-floppy-disk`, annuler `btn-secondary` + `fa-xmark`, activer `btn-success` + `fa-circle-check`,
désactiver `btn-warning` + `fa-ban`, supprimer `btn-danger` + `fa-trash-can`. « Voir » et
« Modifier » restent sobres : ce sont des lectures. Un « Désactiver » gris qui ouvre une modale
orange se lit comme deux actions distinctes.

## Se connecter sans mot de passe, en développement

Le mode livre `app:dev:login-link <email>` : une URL signée qui ouvre une session pour n'importe
quel compte de fixtures, sans rien taper. Elle sert à parcourir les écrans, et à le faire avec un
compte autre que le sien — un rôle sans permission, un compte inactif, un autre locataire.

```
symfony console app:dev:login-link manager@example.test
```

- **La garde est DOUBLE et elle n'est pas décorative.** La route vit sous `when@dev`
  (`config/routes/dev_login_link.yaml`), le contrôleur et la commande portent `#[When('dev')]`. Une
  route qui authentifie sur simple présentation d'une signature n'a rien à faire en production, et
  `DevLoginLinkTest` vérifie qu'aucune des deux n'existe hors développement.
- **Pas d'attribut `#[Route]` sur le contrôleur**, jamais : `config/routes.yaml` scanne
  `src/Controller/` dans TOUS les environnements, donc un attribut ferait exister l'URL en prod.
  C'est le fichier YAML `when@dev` qui déclare la route — les deux verrous disent la même chose.
- **Le service s'injecte par son NOM** (`security.authenticator.login_link_handler.main`) : Symfony
  en fabrique un par pare-feu, donc `LoginLinkHandlerInterface` seule est ambiguë et l'injection
  échoue avec « cannot be determined ».
- **`DEFAULT_URI` doit porter le vrai hôte du projet.** L'URL est fabriquée hors requête : c'est lui
  qui en donne le schéma et le domaine. Le hook du mode le règle désormais sur
  `https://<projet>.localhost` — laissé au défaut de la recipe (`http://localhost`), le lien sort
  inutilisable, et les mails transactionnels avec.
- **Si le projet redirige mal après connexion**, brancher sur `login_link` le même `success_handler`
  que `form_login`. Sans lui, le lien retombe sur `/`, qui n'est pas toujours le bon point d'entrée
  — dans un projet à espaces multiples, la session EST ouverte mais la page répond 404, ce qui se
  lit comme « le lien n'a pas marché ».
