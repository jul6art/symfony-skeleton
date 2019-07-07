# cd /home/symfony-skeleton/public_html/

set -e

echo '--- FILES LOADING ---'
composer install --no-suggest
php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
yarn install
yarn encore dev
php bin/console cache:clear --env=dev
php bin/console cache:warmup --env=dev

echo '--- DATABASE LOADING ---'
php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction
php bin/console audit:clean --no-confirm

# echo '--- TESTS LAUNCHING ---'
# ./vendor/bin/simple-phpunit > tests_output.txt
# echo 'tests output saved in tests_output.txt file'
# cat tests_output.txt

echo '--- SUCCESSFULL DEPLOY ---'