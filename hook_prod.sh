cd /home/symfony-skeleton/public_html/

set -e

echo '--- FILES LOADING ---'
composer install --no-suggest
php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
php bin/console fos:js-routing:dump --format=js --target=public/js/fos_js_routes.js
yarn install
yarn encore production
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo '--- DATABASE LOADING ---'
php bin/console doctrine:migrations:migrate --no-interaction
## si cette ligne crash, vider la db à la main
php bin/console doctrine:fixtures:load --no-interaction --env=dev
php bin/console audit:clean --no-confirm
php bin/console lexik:translations:import -f -c

echo '--- FILES PERMISSIONS ---'
sudo chmod -R 777 /home/symfony-skeleton/public_html/var

echo '--- CHECK VULNERABILITIES ---'
php bin/console security:check

echo '--- TESTS LAUNCHING ---'
./vendor/bin/simple-phpunit

echo '--- OPTIMISATIONS ---'
composer dump-autoload --optimize --no-dev --classmap-authoritative

echo '--- SUCCESSFULL DEPLOY ---'
