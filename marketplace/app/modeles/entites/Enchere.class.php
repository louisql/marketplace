<?php

/**
 * Classe de l'entité Utilisateur
 *
 */
class Enchere
{
  private $utilisateur_id;
  private $utilisateur_nom;
  private $utilisateur_prenom;
  private $utilisateur_courriel;
  private $utilisateur_password;
  private $utilisateur_profil;
  private $mise_montant;
  private $enchere_id;
  private $enchere_dateDebutEnchere;
  private $enchere_dateFinEnchere;
  private $enchere_idUtilisateur;
  private $enchere_nomTimbre;
  private $miseMontant;
  private $dateCreationTimbre;
  private $paysOrigineTimbre;
  private $conditionTimbre;
  private $dimensionsTimbre;
  private $couleurTimbre;
  private $descriptionTimbre;


  
  private $erreurs = array();



  /**
   * Constructeur de la classe
   * @param array $proprietes, tableau associatif des propriétés 
   *
   */ 
  public function __construct($proprietes = []) {
    $t = array_keys($proprietes);
    foreach ($t as $nom_propriete) {
      $this->__set($nom_propriete, $proprietes[$nom_propriete]);
    } 
  }

  /**
   * Accesseur magique d'une propriété de l'objet
   * @param string $prop, nom de la propriété
   * @return property value
   */     
  public function __get($prop) {
    return $this->$prop;
  }

  // Getters explicites nécessaires au moteur de templates TWIG
  public function getUtilisateur_id()               { return $this->utilisateur_id; }
  public function getEnchere_id()                   { return $this->enchere_id; }
  public function getEnchere_dateDebutEnchere()     { return $this->enchere_dateDebutEnchere; }
  public function getEnchere_dateFinEnchere()       { return $this->enchere_dateFinEnchere; }
  public function getEnchere_idUtilisateur()        { return $this->enchere_idUtilisateur; }
  public function getEnchere_nomTimbre()            { return $this->enchere_nomTimbre; }
  public function getMise_Montant()                 { return $this->miseMontant;}
  
  public function getDateCreationTimbre()           { return $this->dateCreationTimbre;}
  public function getPaysOrigineTimbre()           { return $this->paysOrigineTimbre;}
  public function getConditionTimbre()           { return $this->conditionTimbre;}
  public function getDimensionsTimbre()           { return $this->dimensionsTimbre;}
  public function getCouleurTimbre()           { return $this->couleurTimbre;}
  public function getDescriptionTimbre()           { return $this->descriptionTimbre;}
  


  public function getErreurs()              { return $this->erreurs; }
  
  /**
   * Mutateur magique qui exécute le mutateur de la propriété en paramètre 
   * @param string $prop, nom de la propriété
   * @param $val, contenu de la propriété à mettre à jour    
   */   
  public function __set($prop, $val) {
    $setProperty = 'set'.ucfirst($prop);
    $this->$setProperty($val);
  }

  /**
   * Mutateur de la propriété miseMontant
   * @param int $enchere_id
   * @return $this
   */    
  public function setMiseMontant($miseMontant) {
    $this->miseMontant = $miseMontant; // à remplacer avec des contrôles
  }    

  public function setMise_Montant($miseMontant){
    $this-> setMiseMontant($miseMontant);
  }
  /**
   * Mutateur de la propriété dateCreationTimbre
   * @param int $enchere_id
   * @return $this
   */    
  public function setDateCreationTimbre($dateCreationTimbre) {
    $this->dateCreationTimbre = $dateCreationTimbre; // à remplacer avec des contrôles
  }    

  // public function setMise_Montant($dateCreationTimbre){
  //   $this-> setDateCreationTimbre($dateCreationTimbre);
  // }

  
  /**
   * Mutateur de la propriété paysOrigineTimbre
   * @param int $enchere_id
   * @return $this
   */    
  public function setPaysOrigineTimbre($paysOrigineTimbre) {
    $this->paysOrigineTimbre = $paysOrigineTimbre; // à remplacer avec des contrôles
  }    

  // public function setMise_Montant($paysOrigineTimbre){
  //   $this-> setPaysOrigineTimbre($paysOrigineTimbre);
  // }

  /**
   * Mutateur de la propriété conditionTimbre
   * @param int $enchere_id
   * @return $this
   */    
  public function setConditionTimbre($conditionTimbre) {
    $this->conditionTimbre = $conditionTimbre; // à remplacer avec des contrôles
  }    

  // public function setMise_Montant($conditionTimbre){
  //   $this-> setConditionTimbre($conditionTimbre);
  // }

  /**
   * Mutateur de la propriété dimensionsTimbre
   * @param int $enchere_id
   * @return $this
   */    
  public function setDimensionsTimbre($dimensionsTimbre) {
    $this->dimensionsTimbre = $dimensionsTimbre; // à remplacer avec des contrôles
  }    

  // public function setMise_Montant($dimensionsTimbre){
  //   $this-> setDimensionsTimbre($dimensionsTimbre);
  // }
  /**
   * Mutateur de la propriété miseMontant
   * @param int $enchere_id
   * @return $this
   */    
  public function setCouleurTimbre($couleurTimbre) {
    $this->couleurTimbre = $couleurTimbre; // à remplacer avec des contrôles
  }    

  // public function setMise_Montant($couleurTimbre){
  //   $this-> setCouleurTimbre($couleurTimbre);
  // }
  /**
   * Mutateur de la propriété descriptionTimbre
   * @param int $enchere_id
   * @return $this
   */    
  public function setDescriptionTimbre($descriptionTimbre) {
    $this->descriptionTimbre = $descriptionTimbre; // à remplacer avec des contrôles
  }    

  // public function setMise_Montant($descriptionTimbre){
  //   $this-> setDescriptionTimbre($descriptionTimbre);
  // }

  // /**
  //  * Mutateur de la propriété enchere_id 
  //  * @param int $enchere_id
  //  * @return $this
  //  */    
  // public function setEnchere_nomTimbre($enchere_nomTimbre) {
  //   $this->enchere_nomTimbre = $enchere_nomTimbre; // à remplacer avec des contrôles
  // }    

  // public function setNomTimbre($enchere_nomTimbre){
  //   $this-> setEnchere_nomTimbre($enchere_nomTimbre);
  // }

  /**
   * Mutateur de la propriété nomTimbre
   * @param int $enchere_id
   * @return $this
   */    
  public function setEnchere_id($enchere_id) {
    $this->enchere_id = $enchere_id; // à remplacer avec des contrôles
  }    

  public function setIdEnchere($enchere_id){
    $this-> setEnchere_id($enchere_id);
  }

  // /**
  //  * Mutateur de la propriété idDerniereEnchere 
  //  * @param int $idDerniereEnchere
  //  * @return $this
  //  */    
  // public function setEnchere_id($enchere_id) {
  //   $this->enchere_id = $enchere_id; // à remplacer avec des contrôles
  // }    

  // public function setIdEnchere($enchere_id){
  //   $this-> setEnchere_id($enchere_id);
  // }

   /**
   * Mutateur de la propriété utilisateur_id 
   * @param int $utilisateur_id
   * @return $this
   */    
  public function setUtilisateur_id($utilisateur_id) {
    $this->utilisateur_id = $utilisateur_id; // à remplacer avec des contrôles
  }    

  public function setIdUtilisateur($utilisateur_id){
    $this-> setUtilisateur_id($utilisateur_id);
  }


  /**
   * Mutateur de la propriété enchere_dateFinEnchere
   * @param int $enchere_dateFinEnchere
   * @return $this
   */    
  public function setEnchere_dateFinEnchere($enchere_dateFinEnchere) {
    unset($this->erreurs['dateFinEnchere']);
    date_default_timezone_set('America/New_York');

    $currentDate = new DateTime();
    $currentDate = date_format($currentDate, "d-m-Y");

    $inputDate = new DateTime($enchere_dateFinEnchere);
    $inputDate = date_format($inputDate, "d-m-Y");


    if ($enchere_dateFinEnchere == ''){
      $this->erreurs['dateFinEnchere'] = "Veuillez entrez une date";
    }

    if ($inputDate < $currentDate){
      $this->erreurs['dateFinEnchere'] = "L'enchère ne peut pas commencer avant aujourd'hui";
    }
    
    $this->enchere_dateFinEnchere = $enchere_dateFinEnchere; // à remplacer avec des contrôles
  }    

  public function setDateFinEnchere($utilisateur_nom){
    $this -> setEnchere_dateFinEnchere($utilisateur_nom);
  }

  /**
   * Mutateur de la propriété enchere_dateFinEnchere
   * @param int $enchere_dateFinEnchere
   * @return $this
   */    
  public function setEnchere_idUtilisateur($enchere_idUtilisateur) {
    $this->enchere_idUtilisateur = $enchere_idUtilisateur; // à remplacer avec des contrôles
  }    

  

  // public function setIdEnchere($enchere_dateDebutEnchere){
  //   $this-> setEnchere_dateDebutEnchere($enchere_dateDebutEnchere);
  // }

  /**
   * Mutateur de la propriété enchere_dateDebutEnchere
   * @param int $enchere_dateDebutEnchere
   * @return $this
   */    
  public function setdateDebutEnchere($enchere_dateDebutEnchere) {
    unset($this->erreurs['dateDebutEnchere']);

    date_default_timezone_set('America/New_York');

    $currentDate = new DateTime();
    $currentDate = date_format($currentDate, "d-m-Y");

    $inputDate = new DateTime($enchere_dateDebutEnchere);
    $inputDate = date_format($inputDate, "d-m-Y");

    if ($enchere_dateDebutEnchere === ''){

      $this->erreurs['dateDebutEnchere'] = "Veuillez entrez une date";
      
    }
    if ($inputDate < $currentDate){
      // echo('<pre>');
      // var_dump($inputDate);
      // var_dump($currentDate);
      
      $this->erreurs['dateDebutEnchere'] = "L'enchère doit commencer aujourd'hui au plus tôt";
    }
    
    $this->enchere_dateDebutEnchere = $enchere_dateDebutEnchere; // à remplacer avec des contrôles
  }   

  public function setEnchere_dateDebutEnchere($utilisateur_nom){
    $this -> setdateDebutEnchere($utilisateur_nom);
  }


    /**
   * Mutateur de la propriété utilisateur_nom 
   * @param string $utilisateur_nom
   * @return $this
   */    
  public function setNomTimbre($enchere_nomTimbre) {
    unset($this->erreurs['enchere_nomTimbre']);
    $enchere_nomTimbre = trim($enchere_nomTimbre);

    $this->enchere_nomTimbre = $enchere_nomTimbre; // à remplacer avec des contrôles
  }

  public function setEnchere_nomTimbre($enchere_nomTimbre){
    $this -> setNomTimbre($enchere_nomTimbre);
  }

  public function setNomUtilisateur($enchere_nomTimbre){
    $this -> setNomTimbre($enchere_nomTimbre);
  }
}