<?php

/**
 * Classe de l'entité Utilisateur
 *
 */
class Utilisateur
{
  private $utilisateur_id;
  private $utilisateur_nom;
  private $utilisateur_prenom;
  private $utilisateur_courriel;
  private $utilisateur_password;
  private $utilisateur_profil;

  const PROFIL_ADMINISTRATEUR = "administrateur";
  const PROFIL_VENDEUR        = "vendeur";
  const PROFIL_MEMBRE         = "membre";
  
  private $erreurs = array();

  const STATUT_MEMBRE           = 1;
  const STATUT_VENDEUR          = 2;
  const STATUT_ADMINISTRATEUR   = 3;


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
  public function getUtilisateur_id()       { return $this->utilisateur_id; }
  public function getUtilisateur_nom()      { return $this->utilisateur_nom; }
  public function getUtilisateur_prenom()   { return $this->utilisateur_prenom; }
  public function getUtilisateur_courriel() { return $this->utilisateur_courriel; }
  public function getUtilisateur_password() { return $this->utilisateur_password; }
  public function getUtilisateur_profil()   { return $this->utilisateur_profil; }
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
   * Mutateur de la propriété utilisateur_nom 
   * @param string $utilisateur_nom
   * @return $this
   */    
  public function setUtilisateur_nom($utilisateur_nom) {
    unset($this->erreurs['nom']);
    $utilisateur_nom = trim($utilisateur_nom);
    $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
    if (!preg_match($regExp, $utilisateur_nom)) {
      $this->erreurs['nom'] = "Au moins 2 caractères alphabétiques pour chaque mot.";
    }
    $this->utilisateur_nom = $utilisateur_nom; // à remplacer avec des contrôles
  }

  public function setNomUtilisateur($utilisateur_nom){
    $this -> setUtilisateur_nom($utilisateur_nom);
  }

  /**
   * Mutateur de la propriété utilisateur_prenom 
   * @param string $utilisateur_prenom
   * @return $this
   */    
  public function setUtilisateur_prenom($utilisateur_prenom) {
    unset($this->erreurs['prenom']);
    $utilisateur_prenom = trim($utilisateur_prenom);
    $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';
    if (!preg_match($regExp, $utilisateur_prenom)) {
      $this->erreurs['prenom'] = "Au moins 2 caractères alphabétiques pour chaque mot.";
    }
    $this->utilisateur_prenom = $utilisateur_prenom; // à remplacer avec des contrôles
  }

  public function setPrenomUtilisateur($utilisateur_prenom){
    $this -> setUtilisateur_prenom($utilisateur_prenom);
  }
  
  /**

   * Mutateur de la propriété utilisateur_password
   * @param string $utilisateur_password
   * @return $this
   */    

  public function setUtilisateur_password($utilisateur_password) {
    unset($this->erreurs['password']);
    $regExp = '/^[a-zÀ-ÖØ-öø-ÿ]{2,}( [a-zÀ-ÖØ-öø-ÿ]{2,})*$/i';

    if (!preg_match($regExp, $utilisateur_password)) {
      $this->erreurs['password'] = "Au moins 2 caractères alphabétiques pour le mot de passe.";
    }

    $this->utilisateur_password = $utilisateur_password;
  }

  public function setPasswordUtilisateur($utilisateur_password){
    $this -> setUtilisateur_password($utilisateur_password);
  }

  /**
   * Mutateur de la propriété utilisateur_courriel
   * @param string $utilisateur_courriel
   * @return $this
   */    
  public function setUtilisateur_courriel($utilisateur_courriel) {
    unset($this->erreurs['courriel']);
    if ($utilisateur_courriel !== null && $utilisateur_courriel !== '') { // propriété optionnelle
      $utilisateur_courriel = trim(strtolower($utilisateur_courriel));
      if (!filter_var($utilisateur_courriel, FILTER_VALIDATE_EMAIL)) {
        $this->erreurs['courriel'] = "Format invalide.";
      }
      $this->utilisateur_courriel = $utilisateur_courriel; 
    } else if ($utilisateur_courriel === ''){
      $this->erreurs['courriel'] = "Format invalide.";
    }
  }

  public function setCourrielUtilisateur($utilisateur_courriel){
    $this -> setUtilisateur_courriel($utilisateur_courriel);
  }

  /**
   * Mutateur de la propriété utilisateur_profil
   * @param string $utilisateur_profil
   * @return $this
   */    
  public function setUtilisateur_profil($utilisateur_profil) {
    unset($this->erreurs['profil']);
    if($utilisateur_profil === "1" || $utilisateur_profil === "2" || $utilisateur_profil === "3"){

      $this->utilisateur_profil = $utilisateur_profil; // à remplacer avec des contrôles
    } else {
      $this->erreurs['profil'] = "Veuillez choisir une option de la liste.";

    }
  }

  public function setRole_idRole($utilisateur_profil){
    $this -> setUtilisateur_profil($utilisateur_profil);

  }

    /**
   * Génération d'un mot de passe aléatoire dans la propriété utilisateur_mdp
   * @return $this
   */    
  public function generer_mdp() {
    // $mdp = "!a2Rt67&qsd"; // à remplacer par une génération aléatoire

    /* Référence fonction https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php */
   
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    $mdp = implode($pass);

    $this->utilisateur_mdp = $mdp;
 
    return $this;
  }
}