export TERM=xterm
set -e

echo "Starting deployment..."

(php artisan down) || true

git pull origin main

composer install --optimize-autoloader --no-dev --no-interaction

php artisan cache:clear
php artisan config:clear

php artisan optimize

php artisan migrate --force

php artisan up

echo "Deployment completed successfully."