# cd /home/symfony-skeleton/public_html/

set -e

echo '--- DATABASE LOADING ---'
# php bin/console doctrine:schema:drop --forcess
# php bin/console doctrine:schema:update --force
# php bin/console doctrine:fixtures:load --no-interaction

echo '--- FILES LOADING ---'
# yarn install
composer install --no-suggest
# php bin/console assetic:dump
php bin/console cache:clear
php bin/console cache:warmup

# echo '--- TESTS LAUNCHING ---'
# ./vendor/bin/simple-phpunit > tests_output.txt
# echo 'tests output saved in tests_output.txt file'
# cat tests_output.txt