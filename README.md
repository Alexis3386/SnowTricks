# SnowTricks

[![SymfonyInsight](https://insight.symfony.com/projects/e3661554-3a4f-4d31-a94f-745bcf9dfe80/mini.svg)](https://insight.symfony.com/projects/e3661554-3a4f-4d31-a94f-745bcf9dfe80)

SnowTricks est un site communautaire dédié aux figures de snowboard. Il permet aux utilisateurs de partager leurs
astuces et conseils pour réaliser des tricks sur la neige.

## Environnement

Le projet a été développé en utilisant les technologies suivantes :

PHP 8.1
Composer 2.3.5
Symfony 6.2.8
Bootstrap 5.3.0
jQuery 3.6.4

## Installation

1. Cloner le repository GitHub : https://github.com/Alexis3386/SnowTricks.git
2. Copier le fichier .env en .env.local et renseigner les informations de connexion à la base de données et de
   configuration SMTP.
3. Télécharger et installer les dépendances du projet avec Composer : composer install
4. Créer la base de données : php bin/console doctrine:database:create ou symfony console doctrine:database:create si
   vous utilisez le CLI Symfony.
5. Appliquer les migrations pour créer les différentes tables de la base de données : php bin/console doctrine:
   migrations:migrate ou symfony console doctrine:migrations:migrate si vous utilisez le CLI Symfony.
6. Installer les fixtures pour avoir une démo de données fictives : php bin/console doctrine:fixtures:load ou symfony
   console doctrine:fixtures:load. (pour mise en prod lancer la commande doctrine:fixtures:load --group=prod afin de
   charger uniquement les catégories de figures)

## Utilisation

Vous pouvez accéder au site en exécutant le serveur Symfony : symfony serve. Le site sera accessible à
l'adresse http://localhost:8000.

## Auteur

Alexis3386