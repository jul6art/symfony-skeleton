# cd /home/symfony-skeleton/public_html/

set -e

PATH_COMPOSER="composer"
PATH_PHP="php"
PATH_YARN="yarn"

echo '--- FILES LOADING ---'
${PATH_COMPOSER} install --no-suggest
${PATH_PHP} bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
${PATH_PHP} bin/console fos:js-routing:dump --format=js --target=public/js/fos_js_routes.js
# yarn install
${PATH_YARN} install  --check-files
${PATH_YARN} encore dev
${PATH_PHP} bin/console cache:clear --env=dev
${PATH_PHP} bin/console cache:warmup --env=dev

echo '--- DATABASE LOADING ---'
${PATH_PHP} bin/console doctrine:schema:drop --force
${PATH_PHP} bin/console doctrine:schema:update --force
${PATH_PHP} bin/console doctrine:migrations:migrate --no-interaction
${PATH_PHP} bin/console doctrine:fixtures:load --no-interaction
${PATH_PHP} bin/console audit:clean --no-confirm
${PATH_PHP} bin/console messenger:stop-workers
${PATH_PHP} bin/console skeleton:sessions:purge
${PATH_PHP} bin/console skeleton:messages:consume -l 90

echo '--- TRANSLATIONS LOADING ---'
${PATH_PHP} bin/console lexik:translations:import -f -c
${PATH_PHP} bin/console lexik:translations:export
${PATH_PHP} bin/console bazinga:js-translation:dump public/js/

echo '--- CHECK VULNERABILITIES ---'
${PATH_PHP} bin/console security:check

echo '--- TESTS LAUNCHING ---'
# OLD SYNTAX (to merge)
# ./vendor/bin/simple-phpunit > tests_output.txt
# echo 'tests output saved in tests_output.txt file'
# cat tests_output.txt
# ./vendor/bin/simple-phpunit --coverage-clover data/build/clover.xml
./vendor/bin/simple-phpunit
vendor/bin/php-coverage-badger data/build/clover.xml data/report/coverage.svg

echo '--- SUCCESSFULL DEPLOY ---'