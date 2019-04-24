cd /home/symfony-skeleton/public_html/

set -e

composer install --no-suggest

php bin/console d:m:m -y

php bin/console cache:clear
php bin/console cache:warmup
