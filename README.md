#todoAndCo

Améliorez une application existante de ToDo & Co

////// Environnement utilisé durant le développement: ////////
Symfony 5.4.1 Composer 2.3.10 PhpMyAdmin 5.1.2 Symfony CLI 5.4.17 PHP 8.1.8 MySQL 5.7.36

/////// Installation: ///////////

Clonez ou téléchargez le repository GitHub dans le dossier voulu : git clone https://github.com/Rte29/todo.git

Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier .env

Téléchargez et installez les dépendances du projet avec Composer : composer install

Créez la base de données dans le répertoire du projet : php bin/console doctrine:database:create

Appliquer les migrations des entités: php bin/console doctrine:migrations:migrate

Chargez les fixtures du projet : php bin/console doctrine:fixtures:load

Lancer le serveur local: symfony serve
