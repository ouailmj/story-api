## Installation de l'application

```
git clone git@github.com:multiinfo/mystorytelling-api.git
cd mystorytelling-api
composer install
php bin/console doctrine:schema:update --force
php bin/console hautelook:fixtures:load -n
 
yarn
yarn run encore dev

# Installation des clés SSL ( Windows )
ren var\jwt\public.pem.dist public.pem
ren var\jwt\private.pem.dist private.pem

php bin/console server:run

```

