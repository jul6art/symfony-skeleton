#!/usr/bin/env bash
#
# Hook de la brique « comptes » : la paire de clés JWT, dont l'API et les widgets Select2 ont
# besoin (sans elle, `/api` répond 500 au premier appel authentifié), et l'expéditeur des mails.

set -uo pipefail

cd "${PROJECT_DIR:?}" || exit 1

# ⚠️ `config/services.yaml` de cette brique lie DEUX variables d'environnement qu'aucune recipe de
# Flex ne pose : `MAILER_FROM` et `APP_REGISTRATION_ENABLED`. Un `%env()%` est résolu
# PARESSEUSEMENT, donc l'absence ne se voit pas au boot — elle se voit à la première route qui
# touche le service concerné : `/reset-password` et `/register` répondaient 500, et huit tests de la
# suite livrée échouaient. Le défaut tenait sur deux lignes absentes, et il fallait générer un
# projet pour le voir.
env_default() {
    if [ -f .env ] && ! grep -q "^$1=" .env; then
        printf '    .env : %s posé\n' "$1"
        printf '\n# %s\n%s=%s\n' "$3" "$1" "$2" >> .env
    fi
}

env_default MAILER_FROM 'noreply@localhost' \
    "L'expéditeur des mails transactionnels. Lu par \`config/services.yaml\`."
# ⚠️ `${REGISTRATION:-1}` et non `1` : c'est la valeur de `--no-registration`, comme dans les modes
# web et admin. Écrite en dur, elle ouvrait l'inscription de tout back-office généré « fermé » — la
# route /register répondait 200 sur devinthehood-back (2026-10-09).
env_default APP_REGISTRATION_ENABLED "${REGISTRATION:-1}" \
    "L'inscription publique. À 0, la route /register répond 404 — pas 403, qui confirmerait son existence."

# ⚠️ Fermer l'inscription, c'est AUSSI retirer son lien : la route répond 404, mais la page de
# connexion d'admin-bundle affiche « Create an account » tant que `admin.routes.register` est
# rempli (une route vide masque le lien). Les tests, eux, la gardent ouverte : FormThemeTest éprouve
# le thème sur le formulaire d'inscription, le plus riche du mode.
if [ "${REGISTRATION:-1}" = 0 ]; then
    if [ -f config/packages/admin.yaml ]; then
        printf '    admin.yaml : lien d'"'"'inscription retiré\n'
        sed -i.bak "s/^\(        register:\) admin_security_register$/\1 ''/" config/packages/admin.yaml \
            && rm -f config/packages/admin.yaml.bak
    fi
    if [ -f .env.test ] && ! grep -q '^APP_REGISTRATION_ENABLED=' .env.test; then
        printf '    .env.test : inscription ouverte pour les tests\n'
        printf '\n# Inscription fermée dans le projet, ouverte en test (FormThemeTest).\nAPP_REGISTRATION_ENABLED=1\n' >> .env.test
    fi
fi

printf '    Paire de clés JWT…\n'
if ! symfony console lexik:jwt:generate-keypair --skip-if-exists >/dev/null 2>&1; then
    printf '/!\\ génération de la paire de clés impossible — relancer « make jwt-keypair ».\n' >&2
fi
