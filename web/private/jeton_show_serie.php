<?php
/* Affiche les informations sur une s�rie */

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . '_common.php';

$strVarAction = rVar::add('action', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('action');

switch($strVarAction){
    case 'dl':
        $strVarSerie = rVar::add('serie', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('serie');
        $strVarFile = rVar::add('file', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('file');

        $strVarSerie = (int)$strVarSerie;

        // Num�ro de s�rie inexistant
        $pathToSerie = pathToSerie($strVarSerie);
        if( !file_exists($pathToSerie) ){
            header('Location: ' . URL_PROJECT_PRIVATE . 'jeton_show_serie.php');
            die;
        }

        // Format de fichier incorrecte
        if( !preg_match('@^[a-z0-9_-]+\.pdf$@', $strVarFile) ){
            header('Location: ' . URL_PROJECT_PRIVATE . 'jeton_show_serie.php');
            die;
        }

        $pathToFile = $pathToSerie . $strVarFile;

        // Fichier inexistant
        if( !file_exists($pathToFile) ){
            header('Location: ' . URL_PROJECT_PRIVATE . 'jeton_show_serie.php');
            die;
        }

        sendFileHeader($strVarFile, filesize($pathToFile));
        echo file_get_contents($pathToFile);

        die;

        break;

    case 'show':
        $strVarSerie = rVar::add('serie', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('serie');

        $strVarSerie = (int)$strVarSerie;

        $pathToSerie = pathToSerie($strVarSerie);
        if( !file_exists($pathToSerie) ){
            header('Location: ' . URL_PROJECT_PRIVATE . 'jeton_show_serie.php');
            die;
        }

        $files = array();

        if( $handle = opendir($pathToSerie) ){
            while( false !== ($entry = readdir($handle)) ){
                if( $entry == '.' || $entry == '..' ){
                    continue;
                }

                if( is_file($pathToSerie . $entry) ){
                    if( substr($entry, -4) == '.pdf' ){
                        $files[] = $entry;
                    }
                }
            }
        }

        echo $twig->render('@private/jeton_show_serie-show.html.twig', array(
            'serie' => $strVarSerie,
            'files' => $files,
        ));

        break;

    default:
        // Version alternative pour r�cup�rer les s�ries en se basant sur le CSV plut�t que sur une lecture de r�pertoire
        // $series = createListSerie();
        // $nombreSerie = count($series['series']);

        $arrSerie = array(); // Liste des s�ries

        $pathToSerie = pathToSerie();
        if( file_exists($pathToSerie) ){
            if( $handle = opendir($pathToSerie) ){
                while( false !== ($entry = readdir($handle)) ){
                    if( $entry == '.' || $entry == '..' ){
                        continue;
                    }

                    if( is_dir($pathToSerie . $entry) ){
                        $arrSerie[] = $entry;
                    }
                }
            }
        }

        echo $twig->render('@private/jeton_show_serie.html.twig', array(
            'series' => $arrSerie,
        ));
}