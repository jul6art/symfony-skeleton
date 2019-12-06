cd /home/symfony-skeleton/public_html/

PATH_COMPOSER="composer"
PATH_PHP="php"
PATH_YARN="yarn"

set -e

echo '--- FILES LOADING ---'
${PATH_COMPOSER} install --no-suggest
${PATH_PHP} bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
${PATH_PHP} bin/console fos:js-routing:dump --format=js --target=public/js/fos_js_routes.js
${PATH_YARN} install  --check-files
${PATH_YARN} encore production
${PATH_PHP} bin/console cache:clear --env=prod
${PATH_PHP} bin/console cache:warmup --env=prod

echo '--- DATABASE LOADING ---'
${PATH_PHP} bin/console doctrine:migrations:migrate --no-interaction
## si cette ligne crash, vider la db à la main
${PATH_PHP} bin/console doctrine:fixtures:load --no-interaction --env=dev
${PATH_PHP} bin/console audit:clean --no-confirm
${PATH_PHP} bin/console messenger:stop-workers
${PATH_PHP} bin/console skeleton:sessions:purge
${PATH_PHP} bin/console skeleton:messages:consume -l 90

echo '--- TRANSLATIONS LOADING ---'
${PATH_PHP} bin/console lexik:translations:import -m -c
${PATH_PHP} bin/console lexik:translations:export
${PATH_PHP} bin/console bazinga:js-translation:dump public/js/

# echo '--- FILES PERMISSIONS ---'
sudo chmod -R 777 /home/symfony-skeleton/public_html/var

echo '--- CHECK VULNERABILITIES ---'
${PATH_PHP} bin/console security:check

echo '--- TESTS LAUNCHING ---'
./vendor/bin/simple-phpunit

echo '--- OPTIMISATIONS ---'
${PATH_COMPOSER} dump-autoload --optimize --no-dev --classmap-authoritative

echo '--- SUCCESSFULL DEPLOY ---'
