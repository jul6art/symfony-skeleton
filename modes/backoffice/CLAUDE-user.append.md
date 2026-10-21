
### Comptes et permissions

Trois entités, et la frontière entre elles est le sujet :

- **`User`** — le compte. Il implémente cinq contrats étroits (`UserInterface`,
  `AclUserInterface`, `AdminUserInterface`, `AppearanceAwareInterface`, `TwoFactorAwareInterface`),
  ce qui permet à quatre bundles de s'en servir sans qu'aucun ne l'impose. `getTenant()` rend `null` : pas de locataires
  ici, et le contrat l'a toujours prévu.
- **`RolePermission`** — ce qu'un rôle a le droit de faire. Une ligne par couple, avec un booléen :
  « jamais décidé » et « décidé, et refusé » se distinguent, ce qui compte pour qui vient de
  décocher une case.
- **`UserPermissionOverride`** — une décision par personne, qui l'emporte sur ses rôles, dans les
  deux sens. C'est le sens « refuser » qui justifie la table : sans elle, retirer une permission à
  quelqu'un obligerait à lui inventer un rôle.

`PermissionCodes` et `DefaultRolePermissions` restent dans `src/` **à dessein** : un catalogue est
de la politique, pas de l'infrastructure. Le publier dans un paquet reviendrait à publier le plan
de site du projet.

### Chaque route porte sa décision

- Action avec sujet : `$this->denyAccessUnlessGranted(…, $entity)` en première instruction.
- Action sans sujet : `#[IsGranted(PermissionCodes::…)]` sur la MÉTHODE. Un `#[IsGranted]` de
  classe qui ne vérifie que l'authentification n'est pas une décision.
- Route de masse : le même attribut au niveau agrégat. La boucle par entité est la seconde garde.
- Route publique assumée : `#[IsGranted('PUBLIC_ACCESS')]`, écrite comme les autres.
- Le gabarit affiche un bouton sous l'attribut qui le gouverne. Un lien visible qui répond 403 est
  un bug d'interface ; une règle recopiée dans le gabarit en est un autre.

### Premiers pas

```shell
make db-create db-diff db-migrate
make permissions-seed                                   # les permissions par défaut des rôles
make user-create ARGS="moi@exemple.com --admin"         # le premier administrateur
make assets                                             # npm install + build
make start                                              # puis /admin
```

L'inscription publique crée des comptes **inactifs** : quelqu'un valide. Pour la fermer
complètement, videz `admin.routes.register` — le lien disparaît de la carte de connexion — et
mettez `APP_REGISTRATION_ENABLED=0`, qui fait répondre 404 à la route (et non 403, qui confirmerait
son existence).
