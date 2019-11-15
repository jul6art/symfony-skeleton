# cd /home/symfony-skeleton/public_html/

set -e

echo '--- FILES LOADING ---'
composer install --no-suggest
php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
php bin/console fos:js-routing:dump --format=js --target=public/js/fos_js_routes.js
# yarn install
yarn install  --check-files
yarn encore dev
php bin/console cache:clear --env=dev
php bin/console cache:warmup --env=dev

echo '--- DATABASE LOADING ---'
php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:update --force
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction

echo '--- TRANSLATIONS LOADING ---'
php bin/console lexik:translations:import -f -c
php bin/console lexik:translations:export
php bin/console bazinga:js-translation:dump public/js/
php bin/console audit:clean --no-confirm

echo '--- CHECK VULNERABILITIES ---'
php bin/console security:check

echo '--- TESTS LAUNCHING ---'
# OLD SYNTAX (to merge)
# ./vendor/bin/simple-phpunit > tests_output.txt
# echo 'tests output saved in tests_output.txt file'
# cat tests_output.txt
./vendor/bin/simple-phpunit --coverage-clover data/build/clover.xml
vendor/bin/php-coverage-badger data/build/clover.xml data/report/coverage.svg

echo '--- SUCCESSFULL DEPLOY ---'