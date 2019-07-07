cd /home/symfony-skeleton/public_html/

set -e

echo '--- FILES LOADING ---'
composer install --no-suggest
php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
yarn install
yarn encore production
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo '--- DATABASE LOADING ---'
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction --env=prod
php bin/console audit:clean --no-confirm

echo '--- FILES PERMISSIONS ---'
sudo chmod -R 777 /home/symfony-skeleton/public_html/var

echo '--- SUCCESSFULL DEPLOY ---'