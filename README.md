<p align="center">
    <a href="https://devinthehood.com"><img src="https://github.com/jul6art/symfony-skeleton-generator/blob/master/public/img/logo.png?raw=true" alt="logo dev in the hood" width="400"></a>
</p>

Symfony skeleton generator
==========================

<p align="left">
    <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License"></a>
    <img src="https://img.shields.io/static/v1?label=stable&message=v1&color=0ea5e9" alt="Version">
</p>

# In-house Symfony skeleton

A Symfony project generator with four modes to choose from:

| Mode | Base | Contents |
| --- | --- | --- |
| `web` | `symfony new --webapp` | Twig + local Tailwind & Font Awesome, full account flow (register / login / forgot password / change password), user CRUD |
| `api` | `symfony new` (bare skeleton) | Doctrine, Serializer, Validator, JWT authentication, CORS |
| `api-platform` | `symfony new --api` | API Platform (REST + JSON-LD/Hydra, OpenAPI docs, JWT authentication, optional GraphQL) |
| `admin` | `symfony new --webapp` | EasyAdmin back office themed after the `web` front, accounts and user CRUD included |
| `backoffice` | `symfony new --webapp` | In-house back office: server-driven datatables over API Platform, Mercure live refresh, ACL permission engine, per-user appearance |

This repository **is not a versioned Symfony project**: `symfony new` is what
creates the project (so Flex recipes always stay up to date), and the skeleton
then applies its own packages and files on top.

Every mode builds on two in-house bundles, and the generated code uses them
rather than reinventing their pieces:

- **`jul6art/core-bundle`** — the shared abstractions: `AbstractRepository`,
  `AbstractManager`, `IdTrait`, `FactoryInterface`, `AbstractEvent`, the
  event/entity listener base classes and the `*AwareTrait` setters.
- **`jul6art/auth-bundle`** — the `User` entity, its repository (a password
  upgrader), its manager and its factory. No project ever declares its own
  `App\Entity\User`; validation and serialization rules for that vendor entity
  live in `config/validator/` and `config/serializer/`.

The `backoffice` mode adds five more, and that is most of what it *is*:
`admin-bundle` (the shell, the theme, the appearance screen, the sign-in pages),
`datatable-bundle` (the table engine), `api-bundle` (the API Platform filters),
`acl-bundle` (the permission engine) and `push-bundle` (Mercure). It declares
its own `App\Entity\User`, because a permission engine needs an entity it can
extend — the auth bundle's is concrete, not a mapped superclass.

## One rule across every mode: no route without a voter action

Every generated project carries the same first rule, stated at the top of its
`CLAUDE.md`: **each route carries an explicit access decision, and that decision
lives in a voter.** A route with no decision is a bug, even a public one — silence
does not authorise, it forgets to refuse.

- Controllers: `$this->denyAccessUnlessGranted(UserVoter::EDIT, $user)` as the
  first statement whenever there is a subject, `#[IsGranted(UserVoter::LIST)]` on
  the action when there is none, and
  `#[IsGranted(AuthenticatedVoter::PUBLIC_ACCESS)]` for what is deliberately open.
- API Platform: the decision belongs to the operation (`security:`,
  `securityPostDenormalize:`), and its expression names a voter attribute rather
  than a role. `api_platform.defaults.security` closes anything that forgets to
  declare its own.
- EasyAdmin: `setPermission()` per action and `setEntityPermission()` on the CRUD
  — the generated routes have no `#[Route]` to decorate, so this is where their
  decision goes.
- Roles are the *matter* of a decision, never its *expression*: `ROLE_ADMIN` is
  read inside the voter, `access_control` and a class-level `#[IsGranted]` stay
  the coarse belt.

`App\Security\Voter\AbstractVoter` (shipped in every mode) carries the plumbing:
one attribute per exposed action listed by `attributes()`, the decision in
`decide()`, which only ever sees authenticated accounts. Modes with the accounts
brick ship `UserVoter` (`USER_LIST`, `USER_VIEW`, `USER_CREATE`, `USER_EDIT`,
`USER_DELETE`) and its unit test; the `admin` mode adds `AdminVoter` for the
back-office door itself.

The rule is enforced, not merely written down: every project ships
`tests/Security/RouteAccessDecisionTest.php`, which walks the router and fails
when an `App\` action carries neither a method-level `#[IsGranted]` nor a
`denyAccessUnlessGranted()` call — so a route added without a decision turns
`make qa` red. Firewall-intercepted routes (`app_logout`, `api_login`) are the
one documented exception, listed in the test itself.

Because of that rule `symfony/security-bundle` is installed in every mode,
including `api --no-user`: a route without an access decision is not an option.

## What each mode ships

### `web` — web application

- **No Turbo**: `symfony/ux-turbo`, pulled in by the `--webapp` pack, is removed
  at generation time (`packages-remove.txt`). It imposed its rules on the whole
  project — 422 on invalid forms, redirect-only submissions, a snapshot cache
  that can repaint a stale navigation bar — for no gain on server-rendered
  pages. Add it back with `symfony composer require symfony/ux-turbo` if a
  project wants it. Stimulus stays: it drives the confirmation modals.
- **Tailwind** through `symfonycasts/tailwind-bundle`: a standalone binary, no
  Node build. **Font Awesome Free** is downloaded into `assets/fontawesome/` by
  `make fontawesome` (run by `make install`). Nothing is loaded from a CDN at
  runtime — no remote `src` anywhere in the layout.
- Ready-made account flow: `/register`, `/login` (CSRF, remember me, throttling),
  `/reset-password` (`symfonycasts/reset-password-bundle`, e-mail + one-shot
  token), `/profile/password`. Logout is a POST form with a CSRF token, and
  every sensitive action (delete, logout) asks for confirmation in a modal.
- Public sign-up is a switch: `APP_REGISTRATION_ENABLED=0` in `.env` makes
  `/register` answer 404 and hides the "create an account" links. Generate the
  project already closed with `--no-registration`.
- User CRUD under `/admin/users`, restricted to `ROLE_ADMIN`.
- A `User` entity, a Tailwind form theme, flash messages and a responsive
  layout, all ready to build on.
- First administrator: `make user-create ARGS="me@example.com --admin"`.

### `api` — JSON API

- **JWT authentication** (`lexik/jwt-authentication-bundle`): `POST /api/login`
  returns a token, every other `/api` route expects
  `Authorization: Bearer <token>`. The key pair is generated at install time
  (`make jwt-keypair` to regenerate).
- **CORS** through `nelmio/cors-bundle`, driven by `CORS_ALLOW_ORIGIN`.
- `GET /health` as a database-free smoke test, `GET /api/me` for the account
  behind the token, and accounts created from the console (no public sign-up by
  default).
- `request/test.http` drives every shipped route from the IDE (PhpStorm / VS
  Code HTTP client): the login request stores the token for the following ones,
  and the 401 cases are part of the file. Host and e-mail live in
  `request/http-client.env.json`, the password in the gitignored
  `request/http-client.private.env.json`.

### `admin` — back office

The public side (home, sign in, forgotten password, profile) is the `web` front;
the back office is EasyAdmin, themed to match. The dashboard and the user CRUD
are wired to the same `UserManagerInterface` as everything else, and the password
field maps to the bundle transient `plainPassword`, hashed by the controller.
Accounts are the subject of this mode, so `--no-user` is refused there.

The theme lives in `assets/styles/admin.scss`, compiled by
`symfonycasts/sass-bundle` — a standalone Dart Sass binary, so still no Node in
the project (`make sass`, `make sass-watch`). It rewrites EasyAdmin's design
tokens rather than its selectors: the slate/indigo palette of the front, softer
radii, a sidebar whose active item carries an accent bar, list tables set in a
panel with a sticky header, inputs with a real focus ring, dashboard cards
(`.app-card`) — and the same care given to the dark scheme, which EasyAdmin
switches on from the system preference.

### `backoffice` — in-house back office

The one whose subject is the back office itself: a sidebar shell, tables that
page, sort, filter and refresh themselves, and a permission engine finer than
roles.

- **Server-driven datatables.** A `*DataTableConfigProvider` declares columns,
  filters and row actions in PHP; one Stimulus controller draws them and reads
  the rows from an API Platform collection. Pagination, sorting, global search
  and per-column filters are the API's, not a second implementation.
- **Per-user table preferences.** Every account arranges a table for itself:
  which columns it sees, in what order, plus named views — a saved set of
  filters, one of which can open the table. One optional include per table; a
  column marked `hidden: true` is offered without being shown, which is what
  lets a table declare wide without costing width to whoever does not want it.
  The bundle interprets the preferences, the generated project stores them.
- **Live refresh over Mercure.** A change that touches a visible row reloads the
  page of data; a burst shows a "refresh" banner rather than reloading N times.
  Single-tenant, so the bundle's default topic resolver is the right one.
- **ACL, not just roles.** `user:read`, `user:delete`… granted per role in a
  table an admin screen edits, overridable per person in both directions. The
  catalogue stays in `src/` — it is policy, not infrastructure.
- **Per-user appearance**: light / dark / system, seven accent colours, three
  densities, four text sizes, high contrast, reduced motion. Rendered
  server-side onto `<html>`, so a page never flashes the wrong theme.
- **Accounts**: sign in (CSRF, remember me, throttling), sign up (closable,
  creates inactive accounts), forgotten password, change password, user CRUD
  with bulk actions, and a role × permission grid.
- **Two-factor authentication, offered to every account**: a TOTP code turned on
  from the account menu (QR code, first code to confirm), then asked after the
  password; five wrong codes per quarter of an hour lock it. `admin.two_factor`
  in `config/packages/admin_two_factor.yaml` decides whether it is offered
  (`enabled`) and whether it is required (`required`). Ten single-use **backup
  codes** are drawn when it is turned on and shown once; one stands in for the
  app's code at sign-in and on the account screen, so a lost phone does not
  lock the account (`backup_codes`).
- **A parallel test suite.** `make test` runs `./paratest.sh` — one process per
  core minus two. Nothing to isolate between them: the suite runs on in-memory
  SQLite and every test builds its schema through `SchemaTool`, so each process
  already has its own database. `make test-serial` keeps the single-process
  runner as the reference: a test that only fails in parallel depends on state
  shared between processes, and that test is the one to fix.

> ⚠️ **This is the only mode that needs Node.** The table engine is DataTables 2
> + its Responsive plugin + jQuery + Select2, and it is compiled by Webpack
> Encore — the exact stack these bundles were extracted from, and the only one
> proven end to end today. `make assets` runs `npm install && npm run build`.
> The reasoning, and what it would take to move to AssetMapper, are written in
> the generated `CLAUDE.md`.

### `api-platform` — declarative API

`symfony new --api` plus house rules: pagination bounds, JSON-LD + JSON formats,
`stateless` operations, and make targets for the OpenAPI export and the
interactive docs.

- Same **JWT authentication** as the `api` mode: `POST /api/login` returns a
  token, `/api` expects `Authorization: Bearer <token>`, key pair generated at
  install time. The login endpoint is documented in the OpenAPI spec and
  Swagger UI gets its "Authorize" button.
- The `User` entity is exposed as a resource: `/api/users` for `ROLE_ADMIN`
  only, an account reads its own record, and `/api/me` returns the account
  behind the token through a state provider. The password belongs to no
  serialization group, so it cannot leak.

Every mode also ships a smoke test — public pages and probes answer, protected
areas stay closed — so `make qa` (php-cs-fixer, PHPStan level 8, PHPUnit) is
green on a freshly generated project. Last measured on `backoffice`, 2026-10-09:
**93 tests / 416 assertions in 4.9 s across 16 processes**, PHPStan clean,
php-cs-fixer without a diff.

## Usage

```bash
./bin/new-project my-site --web
./bin/new-project my-api  --api
./bin/new-project my-shop --api-platform
./bin/new-project my-back  --admin
./bin/new-project my-desk --backoffice
./bin/new-project ~/www/foo --web --version=7.4.* --docker
./bin/new-project test --api --dry-run     # prints everything, changes nothing
./bin/new-project my-project               # asks for the mode interactively
```

Options: `--mode=<name>`, `--version=`, `--php=`, `--docker`, `--no-extras`
(bare skeleton, without the skeleton's packages), `--no-registration` (closes
public sign-up), `--no-user` (see below), `--no-git`, `--dry-run`, `--list`,
`--help`.

## Generating without accounts

```bash
./bin/new-project my-site --web --no-user
```

Account management is a **brick**, not a hardcoded part of a mode: sign in, sign
up, forgotten password, profile and user CRUD live in `overlay-user/`,
`packages-user.txt`, `post-install-user.sh`, `gitignore-user.append` and
`CLAUDE-user.append.md`. With `--no-user` none of it is copied and none of the
related packages (`jul6art/auth-bundle`, the reset-password bundle, the JWT
bundle…) are installed — the project keeps its layout, its front and its quality
gate, and `make qa` stays green. What does *not* go away is the access-decision
rule: `symfony/security-bundle` and `AbstractVoter` are still there, and the
remaining routes still declare their decision (a public one, in that case). A mode whose subject *is* accounts declares
`REQUIRES_USER=1` in its `mode.conf` and rejects the flag (`admin` does).

## Translations

English is the default locale, French ships complete, and both are declared in
`framework.enabled_locales`. Interface strings live in `translations/messages.*`
and validation messages in `translations/validators.*`; templates and form
labels only carry keys. The `web` and `admin` modes get a locale switcher in the
navigation bar (`?_locale=fr`, remembered in the session by
`App\EventListener\LocaleListener`); the API modes read `Accept-Language`
instead, since they have no session.

To have it at hand everywhere, add this to `~/.zshrc`:

```bash
sfnew() { /PATH/TO/symfony-skeleton/bin/new-project "$@"; }
```

## What the generator does

1. `symfony new <dir> --version=… --no-git [--webapp|--api] [--docker]`
2. writes `.php-version` (detected PHP version, or `--php=`)
3. `symfony composer require` of `common/packages.txt` +
   `modes/<mode>/packages.txt` (same with `--dev` for the `packages-dev.txt`
   files)
4. copies `common/overlay/` then `modes/<mode>/overlay/` into the project
   (mode files override common ones, and both override the files written by
   Flex recipes — that is intentional: these are your house rules)
5. appends `common/gitignore.append` and `modes/<mode>/gitignore.append` to the
   `.gitignore` generated by Flex (without touching the `###> … ###` blocks
   Flex manages), and `modes/<mode>/CLAUDE.append.md` to `CLAUDE.md`
6. replaces the placeholders in the copied files
7. runs the `post-install.sh` hooks if they exist
8. runs `common/devtools.sh`: the project's workflow map (see below)
9. `git init` + initial commit

Since `composer` is not installed globally on this machine, everything goes
through `symfony composer`. If the `php` on the `PATH` is broken, the script
automatically looks for the most recent PHP ≥ 8.5 in the Homebrew directories.

## DevTools — every project is born documented

Every mode installs [`jul6art/devtools`](https://github.com/jul6art/devtools) as
a dev dependency, and the generation ends — after the mode hooks, which add
code, and before the first commit — with `common/devtools.sh`:

- the bridge is registered in `config/bundles.php` for `dev` and `test` only,
  never `all`: production does not have the class;
- `devtools init` writes `.devtools/` and adds its work areas to `.gitignore`;
  `.devtools/config.xml` then gets the console of the skeleton
  (`symfony console`, in every mode), its exclusions (`assets/vendor`,
  `public/bundles`, `config/jwt`) and pages written in French;
- `devtools claude:install --skill --hooks --mcp --agents`: the skills that
  write the pages and answer from them, the hooks that give Claude Code the
  workflows a file reaches before it edits it and refuse to end a task while
  the gate fails, the MCP server (`mcp/sdk` comes with it) and the verifier;
- a first `workflows:inspect --no-ai`: the facts of every route, command and
  component, in `docs/workflows/`, committed with the skeleton. Claude writes
  the prose later, with `/devtools-inspect`.

`--docker` changes nothing here: `symfony new --docker` puts the services in
containers, not PHP, and `symfony console` is what passes their variables to a
command. The generated `CLAUDE.md` points at `docs/workflows/workflows.md`,
the map to read before modifying a workflow, and the `Makefile` gains
`make docs` and `make docs-check`. `--no-extras` installs none of it.

## Placeholders

Usable in any overlay file:

`{{PROJECT_NAME}}` `{{PROJECT_SLUG}}` `{{PROJECT_SNAKE}}` `{{PROJECT_TITLE}}`
`{{MODE}}` `{{SYMFONY_VERSION}}` `{{PHP_VERSION}}` `{{DATE}}` `{{YEAR}}`

## Structure

```
common/
  packages.txt            # composer require, every mode
  packages-dev.txt        # composer require --dev, every mode (jul6art/devtools among them)
  gitignore.append        # macOS, IDE, node… appended to the Flex .gitignore
  overlay/                # files copied into every project
  post-install.sh         # (optional) post-generation hook
  devtools.sh             # the workflow map, after every hook, before the first commit
modes/
  web/
    mode.conf             # DESCRIPTION + SYMFONY_NEW_FLAGS
    packages.txt          # + packages-dev.txt, gitignore.append
    CLAUDE.append.md
    post-install.sh       # (optional) hook for this mode
    overlay/
  api/
    …
  api-platform/
    …
bin/new-project
```

Both `post-install.sh` hooks run with `PROJECT_DIR`, `PROJECT_NAME`, `MODE`,
`MODE_DIR`, `SKELETON_DIR` and `PHP_VERSION` in the environment. The `web` hook
fetches Font Awesome and builds Tailwind; the `api` hook generates the JWT key
pair. Both degrade gracefully when there is no network — the matching `make`
target can be re-run later.

## Adding a mode

A mode is just a data directory — there is no code to touch. Every file is
optional except `mode.conf`:

| File | Role |
| --- | --- |
| `mode.conf` | `DESCRIPTION` (shown by `--list`), `SYMFONY_NEW_FLAGS`, `NEXT_STEPS` / `NEXT_STEPS_USER` printed once the project is generated, optional `REQUIRES_USER=1` |
| `packages.txt` | `composer require`, on top of `common/packages.txt` |
| `packages-dev.txt` | `composer require --dev` |
| `packages-remove.txt` | `composer remove` — drops packages the base skeleton installs but the mode does not want |
| `gitignore.append` | appended to the `.gitignore` written by Flex |
| `CLAUDE.append.md` | appended to the project's `CLAUDE.md` |
| `overlay/` | files copied into the project (placeholders included) |
| `overlay-user/`, `packages-user.txt`, `post-install-user.sh`, `gitignore-user.append`, `CLAUDE-user.append.md` | the account brick — skipped by `--no-user` |
| `post-install.sh` | hook run at the end of the generation |

Example — a `console` mode for a CLI-only project (no HTTP, no Doctrine):

```bash
mkdir -p modes/console/overlay/.make
cat > modes/console/mode.conf <<'EOF'
DESCRIPTION="Console application (bare skeleton + Console component)"
SYMFONY_NEW_FLAGS=""
EOF
printf 'symfony/console\n' > modes/console/packages.txt
cat > modes/console/overlay/.make/console.mk <<'EOF'
.PHONY: list-commands

list-commands: ## Lists the application commands
	$(CONSOLE) list
EOF
```

`./bin/new-project foo --console` works right away, and `--list` picks the mode
up on its own.

A mode that needs accounts reuses an existing brick rather than rewriting it:
copy `modes/web/overlay-user/` and `modes/web/packages-user.txt` into the new
mode, then adapt. That is exactly how the `admin` mode was built — same front,
same account brick, EasyAdmin in place of the hand written CRUD.

Same idea for a `microservice` mode (bare skeleton + Messenger + HTTP client) or
a `shop` mode (`--webapp` + a catalogue).

License
-------

The Symfony Skeleton Generator is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

&copy; 2026 [jul6art](https://devinthehood.com)
