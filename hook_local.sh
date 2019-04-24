# cd /home/symfony-skeleton/public_html/

set -e

echo '--- DATABASE LOADING ---'
# php bin/console doctrine:schema:drop --force --env=dev
# php bin/console doctrine:schema:update --force --env=dev
# php bin/console doctrine:fixtures:load --no-interaction --env=dev

echo '--- FILES LOADING ---'
# yarn install
composer install --no-suggest
# php bin/console assetic:dump --env=dev
php bin/console cache:warmup --env=dev

# echo '--- TESTS LAUNCHING ---'
# ./vendor/bin/simple-phpunit > tests_output.txt
# echo 'tests output saved in tests_output.txt file'
# cat tests_output.txt