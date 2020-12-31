# Projet TER : Home-Construct
#### Symfony version 3.4

##### Important si utilisation des assetic, pour mettre en prod utilisez ces commandes (voir tuto symfony OCC) :
```
$ php bin/console cache:clear
$ php bin/console assetic:dump
$ php bin/console assetic:dump --env=prod
```

### Commandes utiles pour l'interaction avec la BDD :

##### Supprimer la BDD (dont le nom est dans le fichier parameters.yml) :
```
$ php bin/console doctrine:database:drop --force
```
##### Créer la BDD (dont le nom est dans le fichier parameters.yml) :
```
$ php bin/console doctrine:database:create
```
##### Mettre à jour les entités de la BDD (dont le nom est dans le fichier parameters.yml)
```
$ php bin/console doctrine:schema:update --force
```
##### Charger les Fixtures (jeu de données à inserer dans la bdd) :
```
$ php bin/console doctrine:fixtures:load
```
##### Vérifier si le schéma de la BDD est valide (sinon affiche les erreurs):
```
$ php bin/console doctrine:schema:validate
```

### Commandes utiles pour la gestion des entités :

##### Créer une entité :
```
$ php bin/console doctrine:generate:entity
ou
$ php bin/console make:entity
(mais attention les repository cree ne fonctionnent pas..)
```
##### Générer Getter & Setter pour une entité :
```
$ php bin/console doctrine:generate:entities HomeConstructBuildBundle:GrosOeuvre
```
##### Générer le formulaire pour une entité (créer fichier "GrosOeuvreType" ici) :
```
$ php bin/console generate:doctrine:form HomeConstructBuildBundle:GrosOeuvre
```

## Autres commandes utiles :
##### Créer un Bundle :
```
$ php bin/console generate:bundle
$ php bin/console generate:bundle --namespace=HomeConstruct/BuildBundle
```
##### Mettre à jour les Assets :
```
$ php bin/console assets:install --symlink //lien symbolique permettant de ne pas avoir à installer les assets à chaque modif
$ php bin/console assetic:dump
$ php bin/console assets:install 
```
##### Afficher toutes les routes crées :
```
$ php bin/console debug:router
```
##### Effacer le cache :
```
$ php bin/console cache:clear
```

