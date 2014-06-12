<?php

/**
* Permet le création de répertoires de manière récursive
* @author Draeli
* @param strinf $dir Chemin à créer
* @param int $mode chmod du répertoire
* @return boolean
*/
function createDir($dir, $mode = 0755)
{
	if (is_dir($dir) || @mkdir($dir, $mode)) return true;
	if (!createDir(dirname($dir), $mode)) return false;
	return @mkdir($dir, $mode);
}

/**
* Charge tout un fichier csv contenant la liste des coupons édités à ce jour
* @return array La clé du tableau est également l'identifiant du Coupon
*/
function coupon_csv_load(){
    $csv = array();

    $fileToLoad = PATH_PROJECT_GENERATED . GENERATED_LIST_COUPON_CSV_NAME . '.csv';

    if( file_exists($fileToLoad) ){
        $fopenCsv = fopen($fileToLoad, 'rb');
        while( ($data = fgetcsv($fopenCsv)) !== false ){
            $aCoupon = Coupon::load( $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);

            $csv[$aCoupon->getIdIdentifier()] = $aCoupon;
        }
        fclose($fopenCsv);
    }

    return $csv;
}

/**
* Sauvegarde une liste de coupons
* @param array $coupons Liste de coupons à charger
* @return none
*/
function coupon_csv_save($coupons){
    $fileToSave = PATH_PROJECT_GENERATED . GENERATED_LIST_COUPON_CSV_NAME . '.csv';

    // fais une copie de sauvegarde du précédent fichier
    if( file_exists($fileToSave) ){
        rename($fileToSave, substr($fileToSave,0, -4) . '_' . date('Y-m-d_H-i-s', time()) . '.csv');
    }

    // Prépare le nouveau fichier
    $fopenCsv = fopen($fileToSave, 'wb');
    foreach($coupons as $aCoupon){
        fputcsv($fopenCsv, $aCoupon->save());
    }
}

/* Sauvegarde les coupons pour une série */
function coupon_serie_csv_save($coupons, $numSerie){
    $fileToSave = pathToSerie($numSerie) . GENERATED_LIST_COUPON_CSV_NAME . '.csv';

    // Prépare le nouveau fichier
    $fopenCsv = fopen($fileToSave, 'wb');
    foreach($coupons as $aCoupon){
        fputcsv($fopenCsv, $aCoupon->save());
    }
}

/**
* Permet de créer un répertoire pour chaque "série" de coupon généré
* @param $numSerie Numéro de série du coupon pour lequel on souhaite un répertoire
*/
function createDirSerie($numSerie){
    createDir(pathToSerie($numSerie));
}

function pathToSerie($numSerie = null){
    return PATH_PROJECT_GENERATED . 'serie' . DIRECTORY_SEPARATOR . ( is_null($numSerie) ? '' : (int)$numSerie . DIRECTORY_SEPARATOR );
}

function createListSerie(){
    $coupons = coupon_csv_load();

    $numSerie = 0;
    $series = array(
        'series' => array(), // Numéro de série en clé et en valeur un autre tableau qui contient en clé l'identifiant de coupon (fais pour des raisons de performance si on doit faire des recherches)
        'coupons' => array(), // Liste des coupons avec en clé l'identifiant du coupon et en valeur le numéro de série
    );
    foreach($coupons as $aCoupon){
        $idIdentifier = $aCoupon->getIdIdentifier();
        $id = $aCoupon->getId();

        if( $id == 0 ){
            $numSerie++;
        }

        if( !isset($series['series'][$numSerie]) ){
            $series['series'][$numSerie] = array();
        }

        $series['series'][$numSerie][$idIdentifier] = true;

        $series['coupons'][$idIdentifier] = $numSerie;
    }

    return $series;
}

/* Génère une couleur à partir d'un nombre */
function getColor($num) {
    $hash = md5('color' . $num);
    return 
        substr($hash, 0, 2) . // r
        substr($hash, 2, 2) . // v
        substr($hash, 4, 2) // b
    ;
}

/* Pour avoir réellement tous l'espace de la page pour travailler dessus ! */
function fpdfReset($objFpdf){
    $objFpdf->SetMargins(0, 0, 0);
    $objFpdf->SetAutoPageBreak(false); // Pour éviter les sauts de page de type "jefaiscequejeveuxetjetemmerde"
}

/**
* Prend une chaîne en octet et la rend humainement lisible
*
* @author Draeli
* @param int $size Taille en octet à convertir
* @param int $arrondi Facultaif (Défaut : 0) Nombre de chiffre à afficher après la virgule
* @return string Renvoi une chaîne formaté pour être lisible humainement
*/
function getBytesToHuman($size, $arrondi = 0){
    $count = 0;
    $format = array('o', 'Ko', 'Mo', 'Go', 'To', 'Po', 'Eo', 'Zo', 'Yo');
    while( ($size/1024) > 1 && $count < 8 )
    {
        $size /= 1024;
        ++$count;
    }

    return round($size,$arrondi) . ' ' . $format[$count];
}

/**
* Empêche l'interprétation de tout html à l'affichage
* @param string $text
* @return string
*/
function html_protect($text){
    return str_replace(
        array('&', '<', '>', '"', "'"), 
        array('&amp;', '&lt;', '&gt;', '&quot;', '&#39;'), 
        $text
    );
}

/**
* Vérifie si un nombre appartient à un intervalle donné et renvoie la valeur la plus proche dans l'intervalle valide
* Attention : ce comportement peut être modifié grâce au paramètre $options
* @param int, float, double $val
* @param int, float, double, null $min
* @param int, float, double, null $max
* @param array $options
* 	- (mixed) 'force_default' : Si une valeur est en dehors des intervales (donc infèrieur à min et supèrieur à max), alors on force la valeur
* @return int, float, double
*/
function check_interval($val, $min = null, $max = null, $options = array()){
    if ( isset($options['force_default']) )
        return ( $val < $min || $val > $max ) ? $options['force_default'] : $val;

    return ( !is_null($min) && $val < $min ) ? $min : ( ( !is_null($max) && $val > $max ) ? $max : $val );
}

/**
* Fourni les en-têtes nécessaires à l'envoi forcé d'un fichier
* @author Draeli
*/
function sendFileHeader($strFileName, $intTotalSize){
    $strDate = gmdate(DATE_RFC1123);

    // Pour le cache
    header('Pragma: public');
    header('Cache-Control: must-revalidate, pre-check=0, post-check=0, max-age=0');
    header('Last-Modified: '.gmdate(DATE_RFC1123, time()));

    // Info sur le contenu envoyé
    header('Content-Tranfer-Encoding: none');
    header('Content-Length: ' . $intTotalSize);
    header('Content-Type: application/force-download; name="' . $strFileName . '"');
    header('Content-Disposition: attachment; filename="' . $strFileName . '"');

    // Infos sur la réponse HTTP
    header('Date: ' . $strDate);
    header('Expires: ' . gmdate(DATE_RFC1123, time()+1));
}

function getCurrentUrl(){
    // http://php.net/manual/fr/reserved.variables.server.php
    return 'http' . ( empty($_SERVER['HTTPS']) ? '' : 's' ) . '://' .  str_replace('//', '/', $_SERVER['HTTP_HOST'] . '/' .$_SERVER['PHP_SELF']);
}