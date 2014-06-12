Gestion des jetons
==================

**Ce projet regroupe les éléments mis en place dans le cadre de la génération de jetons.**

Le code est écrit pour tourner aussi bien sur un serveur Linux que Windows; aussi bien sur serveur dédié mutualisé que dédié.

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

Installation
------------
Vérifier que les pré-requis sont remplis puis les fichiers du projet doivent être mis sur le serveur (via GIT, FTP, ...) et vous devrez modifier les valeurs présentes dans le répertoire "app/config.php".
Installer les dépendances via Gomposer (https://getcomposer.org) en faisant : php composer.phar install
en étant à la racine du projet.
Par ailleurs le répertoire "web/private" par défaut est protégé par authentification HTTP via un .htaccess dont le login est "demo" et le mot de passe "demo", vous êtes invité à modifier ces valeurs.
Une fois ceci fais, le projet est déjà fonctionnel cependant je vous invite si vous le pouvez à créer un sous-domaine pour le répertoire "web/public" et un sous-domaine pour le répertoire "web/private", à défaut essayez de faire pointer sur le répertoire "web/" ;)

Licence
-------
Le présent code est distribué sous GPL v3 dont un guide rapide en Français est disponible à cette adresse : http://www.gnu.org/licenses/quick-guide-gplv3.fr.html
et la version d'origine complète en Anglais à cette adresse : http://www.gnu.org/licenses/gpl.html ou http://www.gnu.org/licenses/gpl.txt
En cas de litige, l'auteur étant Français, toute action mené devra être rendu sur le sol Français selon les dispositions règlementaires en cours au moment du recours. Par ailleurs, en cas de litige, la partie plaignante s'engage à trouver toute solution à l'amiable avant un éventuel recours autre.