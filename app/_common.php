<?php
/*

    Fichier commun à la création/manipulation des jetons

    /!\ NOTES POUR LES DÉVELOPPEURS /!\ 
    -----------------------------------
    1 : attention à ne pas supprimer de valeurs/lignes dans le csv généré, cela peut fausser la gestion des coupons.

*/

ini_set('date.timezone', 'Europe/Paris');
header('Content-Type: text/html; charset=utf-8'); // ça m'évite de l'urticaire ;)

// ignore_user_abort(true);
session_start();

define('DIR_NAME_PUBLIC', 'public');
define('DIR_NAME_PRIVATE', 'private');
define('PATH_PROJECT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('PATH_PROJECT_VENDOR', PATH_PROJECT . 'vendor' . DIRECTORY_SEPARATOR); // Répertoire 'vendor'
define('PATH_PROJECT_PUBLIC', PATH_PROJECT . 'web' . DIRECTORY_SEPARATOR . DIR_NAME_PUBLIC . DIRECTORY_SEPARATOR); // Répertoire publique
define('PATH_PROJECT_PRIVATE', PATH_PROJECT . 'web' . DIRECTORY_SEPARATOR . DIR_NAME_PRIVATE . DIRECTORY_SEPARATOR); // Répertoire privé
define('PATH_PROJECT_SRC', PATH_PROJECT . 'src' . DIRECTORY_SEPARATOR); // Contient tous le bordel pour les différentes parties du projet
define('PATH_PROJECT_GENERATED', PATH_PROJECT . 'generated' . DIRECTORY_SEPARATOR); // Contient les éléments générés
define('PATH_PROJECT_TPL', PATH_PROJECT . 'tpl' . DIRECTORY_SEPARATOR); // Contient les fichiers pour la génération d'éléments

chdir(PATH_PROJECT); // Nécessaire ?

define('GENERATED_LIST_COUPON_CSV_NAME', 'list_coupon'); // Nom du fichier où sont stockés les informations des coupons

require 'config.php';
require PATH_PROJECT_SRC . 'function.php'; // les fonctions génériques et aussi pour les jetons
require PATH_PROJECT_VENDOR . 'autoload.php';
require PATH_PROJECT_SRC . 'rVar.php';
require PATH_PROJECT_SRC . 'Coupon.php';
require PATH_PROJECT_SRC . 'libs' . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR . 'qrlib.php'; // http://phpqrcode.sourceforge.net
require PATH_PROJECT_SRC . 'libs' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php'; // http://www.fpdf.org

// Met à disposition des scripts un objet Twig pré-configuré
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(PATH_PROJECT_TPL);
$loader->addPath(PATH_PROJECT_TPL . DIR_NAME_PUBLIC . DIRECTORY_SEPARATOR, 'public');
$loader->addPath(PATH_PROJECT_TPL . DIR_NAME_PRIVATE . DIRECTORY_SEPARATOR, 'private');
$twig = new Twig_Environment($loader, array(
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug()); // http://twig.sensiolabs.org/doc/functions/dump.html
// http://twig.sensiolabs.org/doc/advanced.html
$twig->addGlobal('URL_MIPS', URL_MIPS);
$twig->addGlobal('URL_PROJECT_PUBLIC', URL_PROJECT_PUBLIC);
$twig->addGlobal('URL_PROJECT_PRIVATE', URL_PROJECT_PRIVATE);
$twig->addGlobal('CURRENT_URL', getCurrentUrl());