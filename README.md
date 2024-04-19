**Préalablement, et après installation de Docker, il faut allouer au moins 4 à 6 Go de RAM à Docker, dans les menus de settings.**

Utilisé avec le terminal ubuntu20.0 sous WSL2

# Description de l'architecture

Elle se décrit de la manière suivante :
Placer à la racine d'un même dossier :
Un dossier **container-docker** qui contient le docker-compose ainsi que le repertoire php pour la configuration de l'image web
un dossier **symfony** qui contient le projet symfony

# Les volumes 
1. Un volume "web" appeler par defaut  *symfony_www* dans le fichier docker-compose
2. Un volume "phpMyAdmin" appeler par defaut *symfony_phpmyadmin* dans le fichier docker-compose
3. Un volume "msql" appeler par defaut *symfony_db* dans le fichier docker-compose
4. Un volume 'maildev" appeler par defaut *symfony_maildev* dans le fichier docker-compose


# Configuration
### dans le fichier .env du dossier *container-docker* -> Indiquer le chemin du repertoire qui contiendra le projet symfony
`` LOCAL_PROJECT_ROOT=/home/<username>/projects/model-symfony/symfony ``
*ici model-symfony/ contient le dossier /container-docker et /symfony*

le chemin /var/www/html present dans la configuration (docker-compose.yml et dans le DockerFile du dossier php) correspont au conteneur dans le quel symfony sera installé

# Montage du conteneur
**Terminal ubuntu20.0 avec wsl2**
Avec le terminal se placer sur le dossier *container-docker*
`` docker-compose up  ``
Le premier lancement est long, Docker build l'image Web.


## Lister les conteneurs

   `` docker ps``

## Entrer dans un conteneur

Pour acceder au bash à l'interieur du  conteneur
``docker exec -ti <nom-du-conteneur-www> /bin/bash``

exemple avec le conteneur symfony_www:
``docker exec -ti symfony_www /bin/bash ``

ensuite un fois dans la ligne de commande symfony
`` composer create-project symfony/website-skeleton . ``

pour sortir du bash conteneur 
`` exit ``

## Utiliser VS CODE avec wsl ubuntu
1. pre-requis :
   1. extension Remot - wsl installé  (Microsoft)

dans le terminal linux ubuntu se placer dans le repertoire de note projet symfony et saisir la commande suivante :
`` code . ``

cela va lancer vs code avec le projet courant et Remot WSL ubuntu actif (en bas à gauche de la fenêtre vscode)
ce qui nous permet d'acceder au bash linux dans le terminal de VS code

**normalement il faut dans vscode aller dans l'onglet extensions et reinstaller les extensions "installer dans wsl"**

## droit sur les fichier
Dans le terminal linux se placer dans notre repertoire projet (ici par defaut **/symfony**)
``sudo chmod -R 0777 . ``
et saisir le mot de passe root (normalement initialisé à l'installation de l'image ubuntu dans wsl2)

## Configuration base de donnée dans le .env symfony
dans le fichier
``DATABASE_URL="mysql://root:@db:3306/symfony-docker"``

dans le bash du conteneur
`` php bin/console doctrine:database:create ``

## lancer le serveur symfony
dans le bash du conteneur symfony_www
`` symfony serv ``

## acceder aux interfaces
1. projet symfony : **127.0.0.1:8741**
2. phpmyadmin : **127.0.0.1:8080**


