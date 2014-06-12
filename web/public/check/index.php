<?php
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . '_common.php';

$strVarAction = rVar::add('action', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('action');

$renderVariable = array();

switch($strVarAction){
    case 'check':
        $strVarId = rVar::add('id', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('id');
        $IdIdentifierToCheck = $strVarId;

        break;

    default:
        $IdIdentifierToCheck = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);
        if( $IdIdentifierToCheck === false ){
            $IdIdentifierToCheck = '';
        }
        $IdIdentifierToCheck = (string)$IdIdentifierToCheck;
        $IdIdentifierToCheck = strtolower($IdIdentifierToCheck);
}

if( $IdIdentifierToCheck != '' ){
    if( preg_match('@^[0-9a-f]{32}[0-9a-z]{6}$@', $IdIdentifierToCheck) ){
        $pathCheck = __DIR__ . DIRECTORY_SEPARATOR;
        if( file_exists($pathCheck . $IdIdentifierToCheck . '.html') ){
            echo file_get_contents($pathCheck . $IdIdentifierToCheck . '.html');
            die;
        }
        else{
            echo $twig->render('@public/check/index-wrong.html.twig', array(
                'identifier' => $IdIdentifierToCheck,
            ));
            die;
        }
    }
    else{
        $renderVariable['error'] = 'Identifiant de jeton incorrecte !';
    }
}

echo $twig->render('@public/check/index.html.twig', $renderVariable);