<?php

/**
 * Classe Contrôleur des requêtes de l'application frontend
 */

class Frontend extends Routeur
{

  /**
   * Constructeur qui initialise le contexte du contrôleur  
   */
  public function __construct()
  {
    $this->film_id = $_GET['film_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /* ------------------------ */
  /**
   * Afficher l'Accueil
   */
  public function afficherAccueil()
  {
    (new Vue)->generer(
      'vAccueil',
      [
        'titre' => 'Accueil'
      ],
      'gabarit-frontend'
    );
  }



  /**
   * Afficher le ption
   */
  public function afficherInscription()
  {
    (new Vue)->generer(
      'vInscription',
      [
        'titre' => 'Inscription'
      ],
      'gabarit-frontend'
    );
  }

  public function ajouterUtilisateur()
  {
    // if (
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR &&
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_EDITEUR
    // )
    //   throw new Exception(self::ERROR_FORBIDDEN);

    $utilisateur  = [];
    $erreurs = [];
    if (count($_POST) !== 0) {
      // retour de saisie du formulaire
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur); // création d'un objet Auteur pour contrôler la saisie
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) { // aucune erreur de saisie -> requête SQL d'ajout
        $utilisateur_id = $this->oRequetesSQL->ajouterUtilisateur([
          'utilisateur_nom'    => $oUtilisateur->utilisateur_nom,
          'utilisateur_prenom' => $oUtilisateur->utilisateur_prenom,
          'utilisateur_profil' => $oUtilisateur->utilisateur_profil,
          'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
          'utilisateur_password' => $oUtilisateur->utilisateur_password

        ]);
        if ($utilisateur_id > 0) { // test de la clé de l'auteur ajouté
          $this->messageRetourAction = "Ajout de l'auteur numéro $utilisateur_id effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout de l'auteur non effectué.";
        }
        // exit();
        $this->afficherAccueil(); // retour sur la page d'accueil
        exit;
      }
    }

    (new Vue)->generer(
      'vInscription',
      array(
        // 'oUtilisateur' => $this->oUtilisateur,
        'titre'        => 'Inscription',
        'utilisateur'  => $utilisateur,
        'erreurs'      => $erreurs
      ),
      'gabarit-frontend'
    );
  }

  /* ---------GESTION DE LA CONNEXION / DECONNEXION --------------- */

  /**
   * Connecter un utilisateur
   */
  public function connecter()
  {
    $messageErreurConnexion = "";
    if (count($_POST) !== 0) {
      $utilisateur = $this->oRequetesSQL->connecter($_POST);
      if ($utilisateur !== false) {
        $_SESSION['oUtilisateur'] = new Utilisateur($utilisateur);
        $this->oUtilisateur = $_SESSION['oUtilisateur'];
        $this->afficherAccueil();
        exit;
      } else {
        $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
      }
    }

    (new Vue)->generer(
      'vConnexion',
      array(
        'titre'                  => 'Connexion',
        'messageErreurConnexion' => $messageErreurConnexion
      ),
      'gabarit-frontend'
    );
  }

  /**
   * Déconnecter un utilisateur
   */
  public function deconnecter()
  {
    unset($_SESSION['oUtilisateur']);
    $this->connecter();
  }

  /* -------------Gestion Timbres--------------- */


    /**
   * Afficher le catalogue
   */
  public function afficherCatalogue()
  {
    $timbres = $this->oRequetesSQL->getTimbres();
    

    // $url 
    

    (new Vue)->generer(
      'vCatalogue',
      [
        'titre' => 'Catalogue',
        'timbres' => $timbres,
        
      ],
      'gabarit-frontend'
    );
  }
 

}
