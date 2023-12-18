
# Projet de Gestion de Temps Pomodoro avec Symfony

## Aperçu

Ce projet Symfony est une application de gestion du temps basée sur la technique Pomodoro, conçue pour aider les utilisateurs à gérer leur temps de travail et leurs pauses.

### État du Projet

Le projet est actuellement en cours de développement. En raison de circonstances imprévues liées à un accident, le projet n'a pas pu être mené à terme. Cependant, les progrès réalisés jusqu'à présent démontrent une solide maîtrise de Symfony et un engagement sérieux tout au long du module.

### Justification du Niveau de Compétence
3/5
Bien que le projet ne soit pas achevé, les compétences et la compréhension de Symfony démontrées tout au long du module sont significatives. Ce projet sert de preuve de l'aptitude à travailler avec des technologies avancées, malgré des défis personnels.

## Installation et Configuration

### Prérequis

- PHP 8 ou supérieur
- Symfony 7
- Composer
- SQLite

### Étapes d'Installation

1. Cloner le dépôt Git :

git clone : https://github.com/LucieEditionsTissot/Pomodoro.git


2. Installer les dépendances :

composer install


3. Configurer la base de données SQLite :
- Créer un fichier `.env.local` à la racine du projet.
- Configurer la chaîne de connexion à la base de données SQLite dans `.env.local`.

4. Créer la base de données :

php bin/console doctrine:database:create

5. Effectuer les migrations :


   php bin/console doctrine:migrations:migrate

6. Lancer le serveur local :


   symfony server:start

Made with love and pain by Lucie.
