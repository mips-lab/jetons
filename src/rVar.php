<?php
// TODO : à modifier pour introduire une meilleur cohérence, une suppression des méthodes inutiles et une chaînabilité des méthodes de setters

/**
* Classe pour récupérer les variables d'entrée de type : POST, GET, COOKIE, SESSION
*/
class rVar
{
	static private $objrVar; // Singleton de l'objet de gestion des variables

	private
		$arrMethods,
		$arrTypes ,
		$arrVars
	;

	/**
	* Ne peut pas être appelé directement pour être certain de n'avoir qu'une seule instance
	*/
	private function __construct(){
		$this->arrMethods = self::getMethodsCallable();
		$this->arrTypes = self::getTypes();

		/**
		* Variables d'entrées déjà récupérées, ce tableau est composé pour chaque variable d'un tableau contenant :
		* value => valeur de la variable
		* type => bool, int, float, string, array
		* method => get, post, cookie, session (une méthode vide indique que la valeur par défaut (soit indiqué, soit correspondant au type) a était prise)
		*/
		$this->arrVars = array();
	}

	static public function getInstance(){
		if ( ! isset(self::$objrVar) )
		{
			$c = __CLASS__;
			self::$objrVar = new $c();
		}

		return self::$objrVar;
	}

	// Types possibles
	static protected function getTypes(){
		return array(
			'int' => true, 
			'bool' => true,
			'float' => true,
			'array' => true
		);
	}

	// Méthodes possibles
	static protected function getMethodsCallable(){
		return array(
			'post', 
			'get', 
			'cookie',
			'session',
		);
	}

	/**
	* Permet d'ajouter la récupération d'une donnée dans la classe
	*
	* @param string $strName : Nom de la variable à appeler
	* @param array $arrOptions : tableau contenant les différentes options facultatives
	* 	- (mixed - default : string) type
	* 	- (string|array) method : les valeurs possibles pour le contenu sont : get, post, cookie, session par priorité. La valeur par défaut est : defaut = array('post', 'get')
	* 	- (mixed) default : valeur par défaut | defaut = '' pour string, 0 pour int et float, false pour bool
	* @return this
	*/
	static public function add($strName, $arrOptions = array()){
		$strName = trim((string)$strName);

		// Il faut que le nom de la variable soit une chaine
		if ( $strName == '' )
			return false;

		$objrVar = self::getInstance();

		if ( isset($arrOptions['method']) ) { // Vérifie si les méthodes sélectionnées sont valides
			settype($arrOptions['method'], 'array');
			$arrOptions['method'] = array_intersect(array_unique($arrOptions['method']), $objrVar->arrMethods);
		}
		else { // Méthodes vérifiés par défaut
			$arrOptions['method'] = array(
				'post', 
				'get'
			);
		}

		// Vérification que le type choisi est valide
		$arrOptions['type'] = isset($arrOptions['type']) ? (string)$arrOptions['type'] : 'string';
		if ( ! isset($objrVar->arrTypes[$arrOptions['type']]) ) {
			$arrOptions['type'] = 'string';
		}

		// Résultat à enregistrer
		$result = array();

		// Récupération de la variable sans traitement
		foreach ( $arrOptions['method'] as $meth )
		{
			// Recherche la valeur en fonction de la méthode
			if ( $meth == 'post' && isset($_POST[$strName]) )
				$value = $_POST[$strName];
			elseif ( $meth == 'get' && isset($_GET[$strName]) )
				$value = $_GET[$strName];
			elseif ( $meth == 'cookie' && isset($_COOKIE[$strName]) )
				$value = $_COOKIE[$strName];
			elseif ( $meth == 'session' && isset($_SESSION)&& isset($_SESSION['rVar']) && isset($_SESSION['rVar'][$strName]) )
				$value = $_SESSION['rVar'][$strName];
			// Si on a rien trouvé, on passe à l'ittération suivante du foreach
			else
				continue;

			// Si on a récupéré une valeur, on sort de la boucle
			$result['value'] = $value;
			$result['method'] = $meth;
			break;
		}

		// Si on a rien récupéré, on met la valeur par défaut
		if ( !isset($result['value']) )
		{
			$result['value'] = isset($arrOptions['default']) ? $arrOptions['default'] : false;
			$result['method'] = '';
		}

		// Vérification du type de la valeur
		settype($result['value'], $arrOptions['type']);

		// On conserve les données récupérée dans l'instance de l'objet
		$result['type'] = $arrOptions['type'];
		$objrVar->arrVars[$strName] = $result;

		return $objrVar;
	}

	/**
	* Retourne la valeur déjà récupéré par l'intermédiaire de add()
	* Si elle n'existe pas, lève une erreur de type utilisateur
	* 
	* @param string $strName Nom de la variable
	* @return mixed valeur de la variable
	*/
	static public function get($strName){
		$objrVar = self::getInstance();

		if ( isset($objrVar->arrVars[$strName]) )
			return $objrVar->arrVars[$strName]['value'];

		Error::call(__CLASS__ . '::get - utilise ' . __CLASS__ . '::add avant l\'appel de la variable' . $strName, Error::TARGETDev);
		return null;
	}

	/**
	* Retourne l'ensemble des variables associés à leur valeur
	* @return array
	*/
	static public function getAll(){
		$objrVar = self::getInstance();

		$arrReturn = array();
		foreach($objrVar->arrVars as $k => $arrInfo)
			$arrReturn[$k] = $arrInfo['value'];

		return $arrReturn;
	}

	/**
	* Retourne la méthode qui a était prise pour le nom donné
	*
	* @param string $strName Nom de la variable
	* @return string Un nom vide indique soit que la variable n'existe pas, soit que la variable lors de sa demande n'était pas disponible dans le contexte GPC indiqué
	*/
	static public function getMethod($strName){
		$objrVar = self::getInstance();

		if ( isset($objrVar->arrVars[$strName]) )
			return $objrVar->arrVars[$strName]['method'];

		return '';
	}

	/**
	* Retourne le type prit pour le nom donné
	*
	* @param string $strName Nom de la variable
	* @return string
	*/
	static public function getType($strName){
		$objrVar = self::getInstance();

		if ( isset($objrVar->arrVars[$strName]) )
			return $objrVar->arrVars[$strName]['method'];

		Error::call(__CLASS__ . '::getType - la variable ' . $strName . ' n\'a pas de type ce qui révèle d\'une erreur.', Error::TARGETDev);
		return '';
	}

	/**
	* Supprime une variable de l'instance
	*
	* @param string $strName
	* @return this
	*/
	static public function delete($strName){
		// Vérification des paramètres
		$strName = (string)$strName;

		$objrVar = self::getInstance();

		if ( isset($objrVar->arrVars[$strName]) ){
			unset($objrVar->arrVars[$strName]);
		}

		return $objrVar;
	}

	/**
	* Assigne une valeur à une variable globale et remplace ou ajoute dans celles de la classe
	*
	* @param string $strName
	* @param undValue $value La valeur doit être du même type que celui donné lors du add sinon elle sera ignoré
	* @return this
	*/
	static public function set($strName, $undValue){
		// Vérification du type des valeurs
		$strName = (string)$strName;

		$objrVar = self::getInstance();

		if ( isset($objrVar->arrVars[$strName]) && call_user_func('is_' . $objrVar->arrVars[$strName]['type'], $undValue) ){
			$objrVar->arrVars[$strName]['value'] = $undValue;
		}

		return $objrVar;
	}

	/**
	* Sauvegarde une variable en SESSION (via l'espace de nom 'rVar' réservé)
	* 
	* @param string $strName Nom de la variable
	* @return this
	*/
	static public function save($strName){
		$objrVar = self::getInstance();

		if ( isset($objrVar->arrVars[$strName]) && isset($_SESSION) ){
            if( !isset($_SESSION['rVar']) ){
                $_SESSION['rVar'] = array();
            }

            $_SESSION['rVar'][$strName] = $objrVar->arrVars[$strName]['value'];
        }

		return $objrVar;
	}
}
?>