<?php
/* Génère aléatoirement des numéros de coupon tout en gardant une trace permettant d'en générer des nouveaux au besoin (avec à chaque fois le nombre désiré) */

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . '_common.php';

$strVarAction = rVar::add('action', array('type' => 'string', 'method' => array('post', 'get'), 'default' => ''))->get('action');

$strVarJetonNombre = rVar::add('jeton-nombre', array('type' => 'string', 'method' => array('post', 'get', 'session'), 'default' => ''))->get('jeton-nombre');
$strVarJetonValue = rVar::add('jeton-value', array('type' => 'string', 'method' => array('post', 'get', 'session'), 'default' => ''))->get('jeton-value');
$strVarGridWidth = rVar::add('jeton-grid-width', array('type' => 'string', 'method' => array('post', 'get', 'session'), 'default' => ''))->get('jeton-grid-width');
$strVarGridHeight = rVar::add('jeton-grid-height', array('type' => 'string', 'method' => array('post', 'get', 'session'), 'default' => ''))->get('jeton-grid-height');
$strVarSizeWidth = rVar::add('jeton-size-width', array('type' => 'string', 'method' => array('post', 'get', 'session'), 'default' => ''))->get('jeton-size-width');
$strVarSizeHeight = rVar::add('jeton-size-height', array('type' => 'string', 'method' => array('post', 'get', 'session'), 'default' => ''))->get('jeton-size-height');

$renderVariable = array();

$renderVariable['var']['jeton-nombre'] = $strVarJetonNombre;
$renderVariable['var']['jeton-value'] = $strVarJetonValue;
$renderVariable['var']['jeton-grid-width'] = $strVarGridWidth;
$renderVariable['var']['jeton-grid-height'] = $strVarGridHeight;
$renderVariable['var']['jeton-size-width'] = $strVarSizeWidth;
$renderVariable['var']['jeton-size-height'] = $strVarSizeHeight;

switch($strVarAction){
    case 'run-1':
        $strVarSubmit = rVar::add('submit', array('type' => 'string', 'method' => array('post', 'get'), 'default' => '2'))->get('submit');

        $strVarSubmit = (int)$strVarSubmit;
        if( $strVarSubmit == 1 ){
            // Rien de spécial, on laisse suivre le programme sur le switch 'run'
        }else{
            header('Location: ' . getCurrentUrl());
            break;
        }
    case 'run':
        $strVarSubmit = rVar::add('submit', array('type' => 'string', 'method' => array('post', 'get'), 'default' => '2'))->get('submit');

        $renderVariable['error'] = array('field' => array());

        try{
            $strVarSubmit = (int)$strVarSubmit;
            if( $strVarSubmit != '1' && $strVarSubmit != '2' ){
                throw new \Exception('Soumission de formulaire incorrecte.');
            }

            $strVarSubmit = (int)$strVarSubmit;
            if( $strVarJetonNombre < 1 || $strVarJetonNombre > 3840 ){
                $renderVariable['error']['field']['jeton-nombre'] = 'Le nombre de jetons à générer doit être supèrieur à 0 et inférieur à 3841.';
                unset($renderVariable['var']['jeton-nombre']);
            }
            else{
                rVar::save('jeton-nombre');
            }

            $strVarJetonValue = (int)$strVarJetonValue;
            if( $strVarJetonValue < 1 || $strVarJetonValue > 99 ){
                $renderVariable['error']['field']['jeton-value'] = 'La valeur des jetons à générer doit être supèrieur à 0 et inférieur à 100.';
                unset($renderVariable['var']['jeton-value']);
            }
            else{
                rVar::save('jeton-value');
            }

            $strVarGridWidth = (int)$strVarGridWidth;
            if( $strVarGridWidth < 10 || $strVarGridWidth > 609 ){
                $renderVariable['error']['field']['jeton-grid-width'] = 'La largeur de grille doit être supèrieur à 9 et inférieur à 610.';
                unset($renderVariable['var']['jeton-grid-width']);
            }
            else{
                rVar::save('jeton-grid-width');
            }

            $strVarGridHeight = (int)$strVarGridHeight;
            if( $strVarGridHeight < 10 || $strVarGridHeight > 456 ){
                $renderVariable['error']['field']['jeton-grid-height'] = 'La hauteur de grille doit être supèrieur à 9 et inférieur à 457.';
                unset($renderVariable['var']['jeton-grid-height']);
            }
            else{
                rVar::save('jeton-grid-height');
            }

            $strVarSizeWidth = (int)$strVarSizeWidth;
            if( $strVarSizeWidth < 10 || $strVarSizeWidth > 609 ){
                $renderVariable['error']['field']['jeton-size-width'] = 'La largeur des jetons doit être supèrieur à 9 et inférieur à 610.';
                unset($renderVariable['var']['jeton-size-width']);
            }
            else{
                rVar::save('jeton-size-width');
            }

            $strVarSizeHeight = (int)$strVarSizeHeight;
            if( $strVarSizeHeight < 10 || $strVarSizeHeight > 456 ){
                $renderVariable['error']['field']['jeton-size-height'] = 'La hauteur des jetons doit être supèrieur à 9 et inférieur à 457.';
                unset($renderVariable['var']['jeton-size-height']);
            }
            else{
                rVar::save('jeton-size-height');
            }

            if( !isset($renderVariable['error']['field']['jeton-size-width'])  && $strVarSizeWidth > $strVarGridWidth ){
                $renderVariable['error']['field']['jeton-size-width'] = 'La largeur de vos jetons ne peut pas excéder celui de la grille !';
                unset($renderVariable['var']['jeton-size-width']);
            }
            else{
                rVar::set('jeton-size-width', '');
                rVar::save('jeton-size-width');
            }

            if( !isset($renderVariable['error']['field']['jeton-size-height'])  && $strVarSizeHeight > $strVarGridHeight ){
                $renderVariable['error']['field']['jeton-size-height'] = 'La hauteur de vos jetons ne peut pas excéder celui de la grille !';
                unset($renderVariable['var']['jeton-size-height']);
            }
            else{
                rVar::set('jeton-size-height', '');
                rVar::save('jeton-size-height');
            }

            if( count($renderVariable['error']['field']) > 0 ){
                throw new \Exception('Votre formulaire contient des erreurs');
            }
        }
        catch(\Exception $e){
            $renderVariable['error']['global'] = $e->getMessage();
        }

        if( isset($renderVariable['error']['global']) && count($renderVariable['error']['global']) > 0 ){
            echo $twig->render('@private/jeton_create.html.twig', $renderVariable);
        }
        else{
            /* PARAMÈTRES */
            /*1**********1*/

            /*
                0 = normal
                1 = visuel : dans ce mode les fichiers sont créés mais la série n'est pas stocké dans le csv, ce mode est particulèrement adapté pour mettre en place de nouveaux visuels. Ce mode donne également des informations sur la génération.
            */
            $modeWork = --$strVarSubmit;

            // Nombre de nouvaux jetons à générer (les clés seront générés de manière unique à la suite des autres)
            $paramNbCleAGenerer = $strVarJetonNombre;

            // Valeur du jeton
            $valeurJeton = $strVarJetonValue;

            // Gestion de la planche de gravuire
            $paramGrilleLargeur = $strVarGridWidth; // Largeur (Axe X) de la planche de gravure exprimé en mm (millimètre)
            $paramGrilleHauteur = $strVarGridHeight; // Hauteur (Axe Y) de la planche de gravure exprimé en mm (millimètre)

            // Gestion des coupons
            $paramCouponLargeur = $strVarSizeWidth; // Largeur du coupon en mm (millimètre)
            $paramCouponHauteur = $strVarSizeHeight; // Hauteur du coupon en mm (millimètre)

                /*
                Gestion spécifique au dessin des coupons (cette partie est lié au dessin de chaque coupon et donc en cas de changement, il est recommandé de bien nettoyer les variables utilisées) */
                /*2**********2*/

                // Paramètres : Logo
                // /!\ $paramACouponLogoLargeur ne doit pas être supèrieur à $paramCouponLargeur
                $paramACouponLogoLargeur = $paramCouponLargeur - (int)floor(($paramCouponLargeur * 10) / 100); // Largeur du logo (actuellement le logo est centré automatiquement sur la largeur)
                // /!\ $paramACouponLogoY ne doit pas être supèrieur à $paramCouponHauteur
                $paramACouponLogoY = ( $paramCouponHauteur * 3 ) / 100; // Déplacement sur l'axe Y du logo

                // Paramètres : SymbolNumSerie
                // Taille de la police de caractère du symbole représentant le numéro de série
                $paramACouponSymbolNumSerieSize = ($paramCouponHauteur * 80) / 100;
                // /!\ $paramACouponSymbolNumSerieY ne doit pas être supèrieur à $paramCouponHauteur
                $paramACouponSymbolNumSerieY = ($paramCouponHauteur * 87) / 100; // Décalage du n° de série en abscisse

                // Paramètres : NumSerie
                // /!\ $paramACouponNumSerieY ne doit pas être supèrieur à $paramCouponHauteur
                $paramACouponNumSerieY = ($paramCouponHauteur * 91.5) / 100; // Décalage du n° de série en abscisse
                $paramACouponNumSerieSizeCheck = ($paramCouponHauteur * 16) / 100; // Taille de la police de caractère du numéro de série

                // Paramètres : Value
                $paramACouponValueX = ($paramCouponLargeur * 46) / 100; // Emplacement en abscisse pour le jeton
                $paramACouponValueY = ($paramCouponHauteur * 59) / 100; // Emplacement en ordonnée pour le jeton
                $paramACouponValueSize = ($paramCouponHauteur * 120) / 100; // Taille de la police de caractère de la valeur du jeton

                // Paramètres : UrlSite
                $paramACouponUrlSite = 'www.mips-lab.net'; // Adresse du site
                $paramACouponUrlSiteY = (int)floor(($paramCouponHauteur * 85) / 100);
                $paramACouponUrlSiteSize = ($paramCouponHauteur * 16) / 100;

                // Paramètres : QRCode
                // Url de base pour la vérification du jeton
                $paramACouponQRCodeURLBase = 'http://jetons.mips-lab.net/check/';
                // /!\ $paramACouponQRCodeLargeur ne doit pas être supèrieur à $paramCouponLargeur
                $paramACouponQRCodeLargeur = ($paramCouponLargeur * 90) / 100; // Largeur du QRCode (actuellement le logo est centré automatiquement sur les deux axes)

                /*2**********2*/

            /*1**********1*/

            if( $modeWork == 0 ){
                ini_set('max_execution_time', '1200'); // 20 minutes ...
                ini_set('display_errors', '0');
            }
            elseif( $modeWork == 1 ){
                ini_set('max_execution_time', '300');
                ini_set('display_errors', '1');
            }

            $timeBegin = time();

            $grilleNbColonne = floor( $paramGrilleLargeur / $paramCouponLargeur ); // Nombre de colonnes sur la grille
            $grilleNbLigne = floor( $paramGrilleHauteur / $paramCouponHauteur ); // Nombre de lignes sur la grille

            $couponsNew = array(); // Tableau contenant uniquement les coupons de la série généré
            $series = createListSerie();

            // Charge les coupons existants s'il y en a
            $coupons = coupon_csv_load();

            // Génération d'une série de coupon
            for($i = 0; $i < $paramNbCleAGenerer; $i++){
                do{
                    $aCoupon = new Coupon($i, $valeurJeton);
                }
                while( isset($coupons[$aCoupon->getIdIdentifier()]) );

                // Ajoute à l'ancien tableau le coupon et dans la foulée garder une version pour un tableau à part
                $coupons[$aCoupon->getIdIdentifier()] = $couponsNew[$aCoupon->getIdIdentifier()] = $aCoupon;
            }

            // La sauvegarde dans le csv n'a lieu que dans le mode "normal"
            if( $modeWork == 0 ){
                coupon_csv_save($coupons); // Sauvegarde des coupons générés
            }

            // Calculs pour le centrage sur la plaque de gravure/découpe (ce qui permet de bloquer la plaque vraiment au bord)
            $adjustX = floor((($paramGrilleLargeur - ($grilleNbColonne * $paramCouponLargeur)) / 2) * 100) / 100;
            $adjustY = floor((($paramGrilleHauteur - ($grilleNbLigne * $paramCouponHauteur)) / 2) * 100) / 100;

            // Création du répertoire pour la série
            $numSerieCourant = count($series['series']) + 1;
            createDirSerie($numSerieCourant);

            $pathToSerieCourant = pathToSerie($numSerieCourant);
            $pathToQrCode = PATH_PROJECT_GENERATED . 'qrcode' . DIRECTORY_SEPARATOR;

            // Nombre de "planches" à créer pour la série
            $nbLayoutToCreateReal = (int)ceil($paramNbCleAGenerer / ($grilleNbColonne * $grilleNbLigne)); // Vrai nombre "à créer"
            $nbLayoutToCreate = $nbLayoutToCreateReal; // Il s'agit du nombre selon les paramètres (cf. en dessous)

            if( $modeWork == 1 ){ // Pour la simulation je limite à 1 planche quoi qu'il arrive
                $nbLayoutToCreate = 1;
            }

            // Détermine si on doit passer en Landscape ou Portrait
            $layoutDirection = $paramGrilleLargeur > $paramGrilleHauteur ? 'L' : 'P';

            for($z = 0; $z < $nbLayoutToCreate; $z++){
                // génération du stuff pour la gravure
                $strCreator = 'Yves Astier';

                $pdfGravureRecto = new FPDF($layoutDirection, 'mm', array($paramGrilleLargeur, $paramGrilleHauteur));
                $pdfGravureRecto->SetCreator($strCreator);
                $pdfGravureRecto->SetAuthor($strCreator);
                $pdfGravureRecto->SetSubject('Coupons - fichier de gravure côté recto : série ' . $numSerieCourant . ' planche n° ' . $z, true);
                fpdfReset($pdfGravureRecto);
                $pdfGravureRecto->AddPage();
                $pdfGravureRecto->AddFont('aierbazzi');
                $pdfGravureRecto->SetFillColor(0, 0, 255);

                $pdfGravureVerso = new FPDF($layoutDirection, 'mm', array($paramGrilleLargeur, $paramGrilleHauteur));
                $pdfGravureVerso->SetCreator($strCreator);
                $pdfGravureVerso->SetAuthor($strCreator);
                $pdfGravureVerso->SetSubject('Coupons - fichier de gravure côté verso : série ' . $numSerieCourant . ' planche n° ' . $z, true);
                fpdfReset($pdfGravureVerso);
                $pdfGravureVerso->AddPage();

                $pdfDecoupe = new FPDF($layoutDirection, 'mm', array($paramGrilleLargeur, $paramGrilleHauteur));
                $pdfDecoupe->SetCreator($strCreator);
                $pdfDecoupe->SetAuthor($strCreator);
                $pdfDecoupe->SetSubject('Coupons - fichier de découpe : série ' . $numSerieCourant . ' planche n° ' . $z, true);
                fpdfReset($pdfDecoupe);
                $pdfDecoupe->SetLineWidth(0.01); // Définition de l'épaisseur des traits
                $pdfDecoupe->AddPage();

                // ajout des coupons
                for($i = 0; $i < $grilleNbColonne; $i++){
                    for($j = 0; $j < $grilleNbLigne; $j++){
                        // Récupération de l'identifiant de coupon
                        $IdIdentifier = key($couponsNew);
                        // Pour les planches qui ne sont pas remplissables à 100%
                        if( $IdIdentifier === false || is_null($IdIdentifier) ){
                            break 2;
                        }
                        next($couponsNew);

                        $IdIdentifierLng = strlen($IdIdentifier);
                        $pathToQrCodeCourant = $pathToQrCode . $IdIdentifier . '.png';
                        $qrcodePixelSize = 8;
                        $qrcodePadding = 0;
                        $svgCode = QRcode::png(strtoupper($paramACouponQRCodeURLBase . $IdIdentifier), $pathToQrCodeCourant, QR_ECLEVEL_L, $qrcodePixelSize, $qrcodePadding, false);

                        $topXRecto = ($i * $paramCouponLargeur) + $adjustX; // Abscisse du coin supèrieur gauche du coupon courant pour le recto
                        $topYRecto = ($j * $paramCouponHauteur) + $adjustY; // Ordonnée du coin supèrieur gauche du coupon courant pour le recto

                        $topXVerso = $paramGrilleLargeur - $adjustX - (($i + 1) * $paramCouponLargeur); // Abscisse du coin supèrieur gauche du coupon courant pour le verso
                        $topYVerso = $topYRecto; // Ordonnée du coin supèrieur gauche du coupon courant pour le verso

                        // Mise en place de la grille de découpe
                        $pdfDecoupe->Rect($topXRecto, $topYRecto, $paramCouponLargeur, $paramCouponHauteur);

                        // Ces rectangles sont ajoutés uniquement pour les tests de visuels
                        if( $modeWork == 1 ){
                            $pdfGravureRecto->Rect($topXRecto, $topYRecto, $paramCouponLargeur, $paramCouponHauteur);
                            $pdfGravureVerso->Rect($topXVerso, $topYVerso, $paramCouponLargeur, $paramCouponHauteur);
                        }

                        $paddingLeft = $paddingRight = ( ($paramCouponLargeur - $paramACouponLogoLargeur) / 2 ); // Pour une simplification des calculs, le padding est basé sur les valeurs du logo

                        // Ajout du logo
                        $pdfGravureRecto->Image(PATH_PROJECT_GENERATED . 'aCoupon_logo.png', $topXRecto + $paddingLeft, $topYRecto + $paramACouponLogoY, $paramACouponLogoLargeur);

                        // Ajout du "symbole" correspondant à l'identifiant du jeton
                        $pdfGravureRecto->SetFont('aierbazzi', '', $paramACouponSymbolNumSerieSize);
                        $symbolLongueurCharacter = ($paramACouponSymbolNumSerieSize * 30) / 100;
                        $symbolMarginLeft = ($symbolLongueurCharacter*10) / 100;
                        for($u = 0; $u < $IdIdentifierLng; $u++){
                            $pdfGravureRecto->SetXY( -($paramGrilleLargeur - $topXRecto + $symbolMarginLeft), $topYRecto + $paramACouponSymbolNumSerieY);
                            $pdfGravureRecto->Cell($symbolLongueurCharacter, 0, $IdIdentifier[$u], 0, 0, 'L', false);
                        }

                        // Ajout de l'identifiant du jeton
                        $pdfGravureRecto->SetFont('courier', '', $paramACouponNumSerieSizeCheck);
                        $pdfGravureRecto->SetXY($topXRecto + $symbolLongueurCharacter, $topYRecto + $paramACouponNumSerieY);
                        $pdfGravureRecto->Text($topXRecto + $symbolLongueurCharacter + $symbolMarginLeft, $topYRecto + $paramACouponNumSerieY, substr($IdIdentifier, 0, (int)floor($IdIdentifierLng / 2)));
                        $pdfGravureRecto->Text($topXRecto + $symbolLongueurCharacter + $symbolMarginLeft, $topYRecto + $paramACouponNumSerieY + ( ($paramACouponNumSerieSizeCheck * 35) / 100), substr($IdIdentifier, (int)floor($IdIdentifierLng / 2)));

                        // Ajout de l'adresse du site
                        $pdfGravureRecto->SetFont('courier', '', $paramACouponUrlSiteSize);
                        $pdfGravureRecto->SetXY($topXRecto + $symbolLongueurCharacter, $topYRecto + $paramACouponUrlSiteY);
                        // $pdfGravureRecto->Cell($paramCouponLargeur, 0, $paramACouponUrlSite, 0, 0, 'L', false);
                        $pdfGravureRecto->Text($topXRecto + $symbolLongueurCharacter + $symbolMarginLeft, $topYRecto + $paramACouponUrlSiteY, $paramACouponUrlSite);

                        // Valeur du jeton
                        $pdfGravureRecto->SetFont('courier', '', $paramACouponValueSize);
                        $pdfGravureRecto->SetXY($topXRecto + $paramACouponValueX, $topYRecto + $paramACouponValueY);
                        $pdfGravureRecto->Cell( ( ($paramCouponLargeur - $paramACouponLogoLargeur) / 2 ) + $paramACouponLogoLargeur - $paramACouponValueX, 0, $valeurJeton, 0, 0, 'C', false);

                        // Ajout du QRCode
                        $pdfGravureVerso->Image($pathToQrCodeCourant, $topXVerso + ( ($paramCouponLargeur - $paramACouponQRCodeLargeur) / 2 ), $topYVerso + ( ($paramCouponHauteur - $paramACouponQRCodeLargeur) / 2 ), $paramACouponQRCodeLargeur); 
                    }
                }

                $pdfGravureRecto->Output($pathToSerieCourant . ( $modeWork == 1 ? 'test' : $z ) . '_recto_gravure.pdf');
                $pdfGravureVerso->Output($pathToSerieCourant . ( $modeWork == 1 ? 'test' : $z ) . '_verso_gravure.pdf');
                $pdfDecoupe->Output($pathToSerieCourant . ( $modeWork == 1 ? 'test' : $z ) . '_decoupe.pdf');
            }
            reset($couponsNew);

            if( $modeWork == 0 ){
                // Sauvegarde des coupons pour la série
                coupon_serie_csv_save($couponsNew, $numSerieCourant);
            }

            $timeEnd = time();

            foreach( $couponsNew as $IdIdentifier => $aCoupon ){
                $strTplToStore = $twig->render('@private/jeton_generated_public_check_page.html.twig', array(
                    'jeton' => array(
                        'identifier' => $IdIdentifier,
                        'value' => $valeurJeton,
                        'date_create' => new DateTime('@' . $aCoupon->getDateCreate()),
                    ),
                ));
                $tplToStorePath = PATH_PROJECT_PUBLIC . 'check' . DIRECTORY_SEPARATOR;
                if( !file_exists($tplToStorePath) ){
                    createDir($tplToStorePath);
                }
                file_put_contents($tplToStorePath . $IdIdentifier . '.html', $strTplToStore);
            }

            // Création d'un fichier récapitulatif
            file_put_contents(
                $pathToSerieCourant . '_infos.txt',
                'Série : ' . $numSerieCourant . PHP_EOL .
                'Jetons générés : ' . $paramNbCleAGenerer . PHP_EOL .
                'Valeur des jetons : ' . $valeurJeton . PHP_EOL .
                'Taille de la planche : ' . $paramGrilleLargeur . '*' . $paramGrilleHauteur . PHP_EOL .
                'Orientation : ' . ( $layoutDirection == 'L' ? 'Landscape' : 'Portrait' ) . PHP_EOL .
                'Planches générés : ' . $nbLayoutToCreate . PHP_EOL .
                'Colonne par planche : ' . $grilleNbColonne . PHP_EOL .
                'Ligne par planche : ' . $grilleNbLigne . PHP_EOL .
                'Taille du jeton : ' . $paramCouponLargeur . '*' . $paramCouponHauteur . PHP_EOL .
                'Temps de génération des planches : ' . ( $timeEnd - $timeBegin ) . ' secondes'
            );
// TODO : ajouter la liste des fichiers créés pour la simulation et le résultat
            if( $modeWork == 0 ){
                // echo $twig->render('@private/jeton_create-run-2.html.twig', $renderVariable);
                header('Location: ' . getCurrentUrl());
                break;
            }
            elseif( $modeWork == 1 ){
                echo $twig->render('@private/jeton_create-run-1.html.twig', array(
                    'jeton' => array(
                        'info-planche-nombre' => $nbLayoutToCreateReal,
                        'info-grille-nombre-colonne' => $grilleNbColonne,
                        'info-grille-nombre-ligne' => $grilleNbLigne,
                        'info-nombre-genere' => $paramNbCleAGenerer,
                        'info-grille-largeur' => $paramGrilleLargeur,
                        'info-grille-hauteur' => $paramGrilleHauteur,
                        'info-largeur' => $paramCouponLargeur,
                        'info-hauteur' => $paramCouponHauteur,
                    ),
                ));
            }
        }

        break;

    default:
        echo $twig->render('@private/jeton_create.html.twig', $renderVariable);
}