cd /home/symfony-skeleton/public_html/

set -e

echo '--- FILES LOADING ---'
yarn install
yarn encore production
composer install --no-suggest
php bin/console cache:clear
php bin/console cache:warmup

echo '--- DATABASE LOADING ---'
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction