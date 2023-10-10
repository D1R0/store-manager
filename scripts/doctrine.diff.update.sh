cd ../
rm -rf migration/*
php bin/console doctrine:migrations:diff -n
php bin/console doctrine:migrations:migrate --no-interaction