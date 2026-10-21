#!/usr/bin/env bash
#
# DevTools (jul6art/devtools, ADR-0040) : le projet naît documenté.
#
# Lancé par new-project APRÈS les hooks des modes — ils ajoutent du code, donc des workflows — et
# AVANT le premier commit, qui embarque `.devtools/`, `docs/workflows/` et `.claude/`.
#
#   1. le pont Symfony en `dev` et `test` seulement, jamais `all` ;
#   2. `.devtools/` (devtools init), puis config.xml : la console du projet, ses exclusions, la langue ;
#   3. Claude Code : les skills, les hooks, le serveur MCP et le vérificateur ;
#   4. une première inspection sans IA, qui écrit la carte des workflows.

set -euo pipefail

cd "${PROJECT_DIR:?}" || exit 1

DEVTOOLS='vendor/bin/devtools'

if [ ! -x "$DEVTOOLS" ]; then
    printf '/!\\ jul6art/devtools absent (--no-extras ?) : le projet naît sans carte des workflows.\n' >&2
    exit 0
fi

# ⚠️ `symfony php` : le PHP de `.php-version`, pas celui du PATH, qui peut être un autre.
devtools() { symfony php "$DEVTOOLS" "$@"; }

# 1. Flex enregistre un bundle de require-dev en `dev` + `test` ; on le garantit quand même : un
#    `all` ferait échouer le démarrage du noyau en production, où la classe n'existe pas.
LINE="    Jul6Art\\\\DevTools\\\\Bridge\\\\Symfony\\\\DevToolsBundle::class => ['dev' => true, 'test' => true],"
if grep -q 'DevToolsBundle::class' config/bundles.php; then
    perl -pi -e "s/^.*DevToolsBundle::class.*\$/$LINE/" config/bundles.php
else
    perl -0pi -e "s/\\n\\];\\s*\$/\\n$LINE\\n];\\n/" config/bundles.php
fi
printf '    config/bundles.php → DevToolsBundle en dev + test\n'

# 2. `.devtools/` : config.xml (le gabarit commenté de DevTools), les schémas, et les zones de
#    travail ajoutées au .gitignore (pending, reports, runs, gate, stats).
devtools init >/dev/null

# La console de tous les modes est `symfony console` — en `--docker` aussi : `symfony new --docker`
# met les services (base, mailer) en conteneurs, pas PHP, et c'est la CLI Symfony qui passe leurs
# variables aux commandes. Les exclusions : ce que l'importmap télécharge, les assets publiés des
# bundles, la paire de clés JWT.
perl -0pi \
    -e 's{<symfony console="bin/console" env="dev" timeout="60"/>}{<symfony console="symfony console" env="dev" timeout="120"/>};' \
    -e 's{\n    <paths/>\n}{\n    <paths>\n        <exclude>assets/vendor</exclude>\n        <exclude>public/bundles</exclude>\n        <exclude>config/jwt</exclude>\n    </paths>\n};' \
    -e 's{\n</devtools>\s*\z}{\n    <!-- Les pages sont rédigées en français ; leurs titres restent ceux du format. -->\n    <language pages="fr"/>\n</devtools>\n};' \
    .devtools/config.xml
grep -q 'console="symfony console"' .devtools/config.xml && grep -q '<language pages="fr"/>' .devtools/config.xml \
    || { printf '/!\\ .devtools/config.xml : le gabarit de DevTools a changé, à compléter à la main.\n' >&2; }
printf '    .devtools/config.xml → console symfony, exclusions du squelette, pages en français\n'

# 3. Claude Code : le skill qui rédige les pages et celui qui y répond, les hooks (impact avant
#    chaque modification, gate en fin de tâche), le serveur MCP, le vérificateur.
devtools claude:install --skill --hooks --mcp --agents >/dev/null
printf '    .claude/ et .mcp.json → skills, hooks, serveur MCP, vérificateur\n'

# 4. La première carte : sans IA, les faits seuls ; Claude rédigera la prose plus tard
#    (/devtools-inspect). Code 1 = des avertissements (un repli statique, une console lente) :
#    la carte est écrite ; seul le code 2 est une erreur.
set +e
devtools workflows:inspect --no-ai >/dev/null
status=$?
set -e
if [ "$status" -gt 1 ]; then
    printf '/!\\ workflows:inspect a échoué (code %d) : relancer « make docs ».\n' "$status" >&2
else
    printf '    docs/workflows/ → %s workflows documentés\n' "$(grep -c '<workflow ' .devtools/index.xml || true)"
fi
