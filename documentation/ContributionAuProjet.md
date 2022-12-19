Améliorez une application existante de ToDo & Co


Guide à l'usage de l'équipe qui reprendra ce projet pour une bonne cohésion du travail en équipe.

Avant propos
- Le projet fonctionne sur PHP 8.1.8
- Le projet est basé sur le framework symfony 6.1.* (Doctrine, Twig et PhpUnit)
- Clone projet : `git clone https://github.com/Rte29/todo.git

1.Contribuer au projet

	-Cloner et Installer le repository sur votre serveur (voir le README.md)
	-Créez une branche à partir de *main* : git checkout -b nom de la branche
	-Ecrivez un Issue sur les modifications que vous allez apporter
	-Ecrivez votre code en respectant les bonnes pratiques
	-Ecrivez des Commit Clairs et precis avant de faire un Push de la branche : git push origin maBranche
	-Mettez à jour vos issues
	-Faites un *Pull Request* et attendez sa validation

2.Les bonnes pratiques 

	a.Le code
    	Vous devez respecter :
	- Le PSR 2 au minimum
	- Les standards du code de Symfony 
	(`https://symfony.com/doc/current/contributing/code/standards.html`)
	- Les conventions du code de Symfony (`https://symfony.com/doc/5.2/contributing/code/conventions.html`)

	b.Les bundles
	- Toute installation de bundle PHP doit obligatoirement se faire avec "Composer"

	c.Git
	Vous devez faire les choses dans cet ordre : 
    	- Nouvelle branche à partir de main duement nomée
    	- Commit Correctement commentés
    	- Issue Correctement commentées et documentées
    	- pull Request OBLIGATOIRE
    	- Seul le chef de projet peut faire un "merge" sur "master" après révision de votre code.
    	- Faire un update sur le code principal : git pull origin master

	d.Tests unitaires et fonctionnels
    	- PhpUnit est à votre disposition pour créer vos tests
    	- Toute nouvelle fonctionnalité doit avoir des tests associés
    	- Vous devez respecter un taux de couverture au-delà de 70%

	e.Diagramme UML
    	- Réalisez des diagrammes UML (UseCase, Class, Sequence) pour les nouvelles fonctionnalités

	f.Architecture de fichier
    	- Respectez l'architecture de symfony 6 pour vos fichiers PHP ( src\Controller\... )
    	- Les vues devront être dans le repertoire templates.


