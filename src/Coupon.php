<?php

/**
* Class représentant un Coupon
*/
class Coupon
{
    const
        ALPHANUM_STRING = 'abcdefghijklmnopqrstuvwxyz0123456789',
        MAX_INT = 2147483647
    ;

    protected
        $idIdentifier = null, // Chaîne de caractère identifiant le coupon
        $nbRand = null, // Nombre aléatoire associé au coupon
        $uniqId = null, // Identifiant aléatoire associé au coupon
        $randedString = null, // Chaîne de caractère aléatoire associé au coupon
        $id = null, // Identifiant incrémental du coupon
        $dateCreate = null, // Date de création du coupon
        $value = null // Valeur du coupon
    ;

    static protected
        $boolGenerate = true
    ;

    public function __construct($id, $value = 1){
        $this->setId($id);

        $this->setDateCreate( time() );

        $this->setValue( $value );

        if( self::$boolGenerate ){
            $this->generate();
        }
    }

    /**
    * Retourne un objet Coupon à partir de valeurs existantes
    */
    static public function load($idIdentifier, $nbRand, $uniqId, $randedString, $id, $dateCreate, $value){
        self::$boolGenerate = false;

        $className = __CLASS__;
        $aCoupon = new $className($id, false);
        $aCoupon
            ->setIdIdentifier($idIdentifier)
            ->setNbRand($nbRand)
            ->setUniqId($uniqId)
            ->setRandedString($randedString)
            ->setId($id)
            ->setDateCreate($dateCreate)
            ->setValue($value)
        ;

        self::$boolGenerate = true;

        return $aCoupon;
    }

    /**
    * Renvoi sous forme de tableau les informations du Coupon.
    * L'ordre des éléments fourni respecte l'ordre de la méthode 'load'.
    */
    public function save(){
        // à partir du moment où on a l'identifiant du coupon on peut considérer l'objet comme sauvegardable
        $idIdentifier = $this->getIdIdentifier();

        if( is_null($idIdentifier) ){
            throw new \Exception('Vous devez générer les informations du coupon avant de vouloir le sauvegarder');
        }

        return array(
            $idIdentifier,
            $this->getNbRand(),
            $this->getUniqId(),
            $this->getRandedString(),
            $this->getId(),
            $this->getDateCreate(),
            $this->getValue(),
        );
    }

    public function generate(){
        $this->setNbRand( $this->generateNbRand() );
        $this->setUniqId( $this->generateUniqId() );
        $this->setRandedString( $this->generateRandedSting() );
        $this->setIdIdentifier( $this->generateIdIdentifier() );
    }

    protected function generateIdIdentifier(){
        $strError = 'Éléments insuffisants pour créer la signature du coupon !';

        $uniqId = $this->getUniqId();
        if( is_null($uniqId) ){
            throw new \Exception( $strError . ' (uniqId miss).');
        }

        $nbRand = $this->getNbRand();
        if( is_null($nbRand) ){
            throw new \Exception( $strError . ' (nbRand miss).');
        }

        $id = $this->getId();
        if( is_null($id) ){
            throw new \Exception( $strError . ' (id miss).');
        }

        $randedString = $this->getRandedString();
        if( is_null($randedString) ){
            throw new \Exception( $strError . ' (randedString miss).');
        }

        return (string)md5( $nbRand . $uniqId . $id ) . $randedString;
    }

    protected function generateNbRand(){
        return (string)mt_rand();
    }

    protected function generateUniqId(){
        return (string)uniqid();
    }

    protected function generateRandedSting(){
        $strRanded = '';
        for($x = 0; $x < 6; $x++){
            $strRanded .= substr(self::ALPHANUM_STRING, mt_rand(0, strlen(self::ALPHANUM_STRING) - 1), 1);
        }

        return $strRanded;
    }

    public function isValidIdIdentifier($idIdentifier){
        if( !is_string($idIdentifier) || !preg_match('@^[0-9a-z]{38}$@', $idIdentifier) ){
            return false;
        }

        return true;
    }

    protected function setIdIdentifier($idIdentifier){
        $idIdentifier = strtolower((string)$idIdentifier);
        if( !$this->isValidIdIdentifier($idIdentifier) ){
            throw new \Exception('Il me faut un IdIdentifier valide pour le coupon !');
        }

        $this->idIdentifier = $idIdentifier;

        return $this;
    }

    public function getIdIdentifier(){
        return $this->idIdentifier;
    }

    public function isValidNbRand($nbRand){
        if( !is_int($nbRand) || $nbRand < 0 || $nbRand > self::MAX_INT ){
            return false;
        }

        return true;
    }

    protected function setNbRand($nbRand){
        $nbRand = (int)$nbRand;
        if( !$this->isValidNbRand($nbRand) ){
            throw new \Exception('Il me faut un nbRand valide pour le coupon !');
        }

        $this->nbRand = $nbRand;

        return $this;
    }

    public function getNbRand(){
        return $this->nbRand;
    }

    public function isValidUniqId($uniqId){
        if( !is_string($uniqId) ){
            return false;
        }

        return true;
    }

    protected function setUniqId($uniqId){
        $uniqId = (string)$uniqId;
        if( !$this->isValidUniqId($uniqId) ){
            throw new \Exception('Il me faut un uniqId valide pour le coupon !');
        }

        $this->uniqId = $uniqId;

        return $this;
    }

    public function getUniqId(){
        return $this->uniqId;
    }

    public function isValidRandedString($randedString){
        if( !is_string($randedString) || !preg_match('@^[0-9a-z]{6}$@', $randedString) ){
            return false;
        }

        return true;
    }

    protected function setRandedString($randedString){
        $randedString = (string)$randedString;
        if( !$this->isValidRandedString($randedString) ){
            throw new \Exception('Il me faut un randedString valide pour le coupon !');
        }

        $this->randedString = $randedString;

        return $this;
    }

    public function getRandedString(){
        return $this->randedString;
    }

    public function isValidId($id){
        if( !is_int($id) || $id < 0 || $id > self::MAX_INT ){
            return false;
        }

        return true;
    }

    protected function setId($id){
        $id = (int)$id;

        if( !$this->isValidId($id) ){
            throw new \Exception('Il me faut un identifiant NUMÉRIQUE pour le coupon !');
        }

        $this->id = $id;

        return $this;
    }

    public function getId(){
        return $this->id;
    }

    public function isValidDateCreate($dateCreate){
        if( !is_int($dateCreate) || $dateCreate < 1402165098 ){ // date > 2014-06-07 20:18
            return false;
        }

        return true;
    }

    protected function setDateCreate($dateCreate){
        $dateCreate = (int)$dateCreate;

        if( !$this->isValidDateCreate($dateCreate) ){
            throw new \Exception('Il me faut un dateCreate valide pour le coupon !');
        }

        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateCreate(){
        return $this->dateCreate;
    }

    public function isValidValue($value){
        if( !is_int($value) || $value < 0 || $value > 100 ){
            return false;
        }

        return true;
    }

    protected function setValue($value){
        $value = (int)$value;

        if( !$this->isValidValue($value) ){
            throw new \Exception('Il me faut un value valide pour le coupon !');
        }

        $this->value = $value;

        return $this;
    }

    public function getValue(){
        return $this->value;
    }
}