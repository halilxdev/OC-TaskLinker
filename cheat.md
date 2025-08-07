# ENTITÉS

## Pour créer une entité
symfony console make:entity


# BASE DE DONNÉES

## Pour relier le projet à la base de donnée
symfony console doctrine:database:create

## En cas de modifications, suppression, ajout d'une entité existante
symfony console make:migration
symfony console doctrine:migrations:migrate

## Fixtures
symfony console doctrine:fixtures:load

## Pour supprimer tout ce qu'il y'a dans la base
symfony console donctrine:database:drop --force