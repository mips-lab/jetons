Gestion des jetons
==================

**Ce projet regroupe les éléments mis en place dans le cadre de la génération de jetons.**

Le code est écrit pour tourner sur un serveur dédié Linux ou Windows. Sur mutualisé ça doit pouvoir tourner mais sans garantie aucune !

*Les fonctionnalités actuels sont* :  
- script de génération de planches de jetons (partie "web/private/")
- vérification des jetons (partie "web/public/")

Script de génération de jetons
------------------------------
- sont pris en compte la taille de la planche, la taille de jeton souhaité et le nombre de jeton à générer
- génère 3 fichiers (recto gravure, verso gravure, découpe)
- stock dans un CSV (donc pas de SGBD nécessaire) les informations sur tous les jetons générés et est capable de prendre en compte ceux précédemment créés pour proposer tous le temps un code unique
- chaque génération donne lieu à la création d'un répertoire "serie" spécifique qui comporte les fichiers générés ainsi qu'un récapitulatif sur la génération et un CSV spécifique à la génération
- lors de la génération du CSV principal, le CSV de la version précédente est gardé

Crédits
-------
Projet créé par Yves ASTIER dans le cadre de l'association Paulla.

Pré-requis
----------
- serveur http : mod_rewrite doit être activé
- php : gd2.so (ou gd2.dll) activé
- composer + un accès au shell du serveur

Installation
------------
Vérifier que les pré-requis sont remplis puis les fichiers du projet doivent être mis sur le serveur (via GIT, FTP, ...) et vous devrez modifier les valeurs présentes dans le répertoire "app/config.php".
Installer les dépendances via Composer (https://getcomposer.org) en faisant : php composer.phar install
en étant à la racine du projet.
Une fois ceci fais, le projet est déjà fonctionnel cependant je vous invite si vous le pouvez à créer un sous-domaine pour le répertoire "web/public" et un sous-domaine pour le répertoire "web/private", à défaut essayez de faire pointer sur le répertoire "web/" ;)

Le répertoire "web/private" devrait être protégé par authentification HTTP au minimum, des fichiers d'exemples sont fournis à la racine du répertoire, à votre charge de mettre à jour avec les informations spécifiques à votre serveur (à savoir créer un login et mot de passe dans .htpasswd puis définir le path correct dans .htaccess et décommenter le tous).

Ceux qui veulent tenter l'installation sur mutualisé, il vous faudra récupérer les fichiers (sur serveur local par exemple) de "vendor/" (donc installés via composer) et les mettres à disposition sur le FTP.

Licence
-------
Le présent code est distribué sous GPL v3 dont un guide rapide en Français est disponible à cette adresse : http://www.gnu.org/licenses/quick-guide-gplv3.fr.html
et la version d'origine complète en Anglais à cette adresse : http://www.gnu.org/licenses/gpl.html ou http://www.gnu.org/licenses/gpl.txt
En cas de litige, l'auteur étant Français, toute action mené devra être rendu sur le sol Français selon les dispositions règlementaires en cours au moment du recours. Par ailleurs, en cas de litige, la partie plaignante s'engage à trouver toute solution à l'amiable avant un éventuel recours autre.