# COMPOSER

## Démarrer ton environnement le matin
docker-compose up -d
## Voir les logs si quelque chose ne va pas
docker-compose logs -f php
## Arrêter tout le soir
docker-compose down

# SYMFONY 

## Créer la web-app
symfony new app --version="7.3.x" --webapp

## Souci loading assets
docker-compose exec php php bin/console assets:install
docker-compose exec php php bin/console asset-map:compile
docker-compose exec php php bin/console cache:clear

# ENTITÉS

## Pour créer une entité
docker-compose exec php php bin/console make:entity

# BASE DE DONNÉES

## Pour supprimer tout ce qu'il y'a dans la base
docker-compose exec php php bin/console doctrine:database:drop --force
## Pour relier le projet à la base de donnée
docker-compose exec php php bin/console doctrine:database:create
## En cas de modifications, suppression, ajout d'une entité existante
docker-compose exec php php bin/console make:migration
docker-compose exec php php bin/console doctrine:migrations:migrate
## Fixtures
docker-compose exec php php bin/console doctrine:fixtures:load

# Visualisation mobile ou autre appareil
echo "=== Accès depuis téléphone/autre appareil ==="
echo "IP de ce Mac : $(ifconfig | grep 'inet ' | grep -v '127.0.0.1' | head -1 | awk '{print $2}')"
echo "App Symfony : http://$(ifconfig | grep 'inet ' | grep -v '127.0.0.1' | head -1 | awk '{print $2}'):8000"
echo "PHPMyAdmin : http://$(ifconfig | grep 'inet ' | grep -v '127.0.0.1' | head -1 | awk '{print $2}'):8080"