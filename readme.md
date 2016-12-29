# Projet Crawler

Le framework Laravel a été ajouté. Les anciens fichiers PHP déjà créés sont dans /vendor/ancienneVersion

## Installation

Pour que l'on ai tous accès au framework, suivez ces étapes :

- Si vous avez déjà cloné le dépôt git sur votre ordinateur, supprimez-le
- Clonez à nouveau le dépôt git

- Faites une copie du fichier ".env.example" et renommez-le ".env"
- Modifiez la partie mysql du fichier avec vos propres informations de connexion

- Dans votre terminal faites "composer update" à la racine de votre dépôt local
- Dans votre terminal faites "php artisan key:generate" à la racine de votre dépôt local
- Pour installer la migration, faites "php artisan migrate:install"
- Pour migrer la base de données, faites "php artisan migrate"
- Faites "composer dumpautoload"
- Pour peupler la table balises, faites "php artisan db:seed"

- Faites pointer votre serveur web dans le répertoire public

Normalement, ça devrait marcher

Lors d'un nouveau pull, réalisez la suite de commande suivante pour mettre à jour la base de données :

- php artisan migrate:reset
- php artisan migrate
- php artisan db:seed
