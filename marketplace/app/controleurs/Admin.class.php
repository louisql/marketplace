<?php

// import Frontend.class.php from

/**
 * Classe Contrôleur des requêtes de l'application admin
 */

class Admin extends Routeur
{

  private $entite;
  private $action;
  private $utilisateur_id;

  private $oUtilisateur;

  private $methodes = [
    'utilisateur' => [
      'l' => 'listerUtilisateurs',
      'a' => 'ajouterUtilisateur',
      'm' => 'modifierUtilisateur',
      'd' => 'deconnecter',
      's' => 'supprimerUtilisateur',
      'generer_mdp' => 'genererNouveauMdp'
    ],
    'accueil' => [
      'l' => 'afficherAccueil'
    ],
    'enchere' => [
      'l' =>'listerEncheres'
    ]

  ];

  private $classRetour = "fait";
  private $messageRetourAction = "";

  /**
   * Constructeur qui initialise le contexte du contrôleur  
   */
  public function __construct()
  {
    $this->entite    = $_GET['entite']    ?? 'accueil';
    $this->action    = $_GET['action']    ?? 'l';
    // $this->auteur_id = $_GET['auteur_id'] ?? null;
    // $this->livre_id  = $_GET['livre_id']  ?? null;
    $this->utilisateur_id  = $_GET['utilisateur_id']  ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Gérer l'interface d'administration 
   */
  public function gererAdmin()
  {
    if (isset($_SESSION['oUtilisateur'])) {
      $this->oUtilisateur = $_SESSION['oUtilisateur'];
      if (isset($this->methodes[$this->entite])) {
        if (isset($this->methodes[$this->entite][$this->action])) {
          $methode = $this->methodes[$this->entite][$this->action];
          $this->$methode();
        } else {
          throw new Exception("L'action $this->action de l'entité $this->entite n'existe pas.");
        }
      } else {
        throw new Exception("L'entité $this->entite n'existe pas.");
      }
    } else {
      $this->connecter();
    }
  }

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
        $this->listerUtilisateurs();
        exit;
      } else {
        $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
      }
    }

    (new Vue)->generer(
      'vAdminUtilisateurConnecter',
      array(
        'titre'                  => 'Connexion',
        'messageErreurConnexion' => $messageErreurConnexion
      ),
      'gabarit-admin-min'
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




  /**
   * Lister les Utilisateurs
   */
  public function listerUtilisateurs()
  {
    // if (
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR &&
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_EDITEUR
    // )
    //   throw new Exception(self::ERROR_FORBIDDEN);

    // exit;
    $utilisateurs = $this->oRequetesSQL->getUtilisateurs();
    (new Vue)->generer(
      'vAdminUtilisateurs',
      array(
        'oUtilisateur'        => $this->oUtilisateur,
        'titre'               => 'Gestion des utilisateurs',
        'utilisateurs'        => $utilisateurs,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction
      ),
      'gabarit-admin'
    );
  }



  /**
   * Ajouter un utilisateur
   */
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
      'vAdminUtilisateurAjouter',
      array(
        'oUtilisateur' => $this->oUtilisateur,
        'titre'        => 'Inscription',
        'utilisateur'  => $utilisateur,
        'erreurs'      => $erreurs
      ),
      'gabarit-frontend'
    );
  }

  /**
   * Modifier un utilisateur identifié par sa clé dans la propriété utilisateur_id
   */
  public function modifierUtilisateur()
  {
    // if (
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR &&
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_EDITEUR
    // )
    //   throw new Exception(self::ERROR_FORBIDDEN);

    if (count($_POST) !== 0) {
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur);
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) {
        if ($this->oRequetesSQL->modifierUtilisateur([
          'utilisateur_nom'       => $oUtilisateur->utilisateur_nom,
          'utilisateur_id'        => $oUtilisateur->utilisateur_id,
          'utilisateur_prenom'    => $oUtilisateur->utilisateur_prenom,
          'utilisateur_courriel'  => $oUtilisateur->utilisateur_courriel,
          'utilisateur_profil'    => $oUtilisateur->utilisateur_profil,
          'utilisateur_password'  => $oUtilisateur->utilisateur_password
          ])) {
            $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id effectuée.";
          } else {
            $this->classRetour = "erreur";
            $this->messageRetourAction = "modification de l'utilisateur numéro $this->utilisateur_id non effectuée.";
          }
        $this->listerUtilisateurs();
        exit;
      }
    } else {
      // chargement initial du formulaire
      // initialisation des champs dans la vue formulaire avec les données SQL de cet auteur  
      $utilisateur  = $this->oRequetesSQL->getUtilisateur($this->utilisateur_id);
      $erreurs = [];
    }

    (new Vue)->generer(
      'vAdminUtilisateurModifier',
      array(
        'oUtilisateur' => $this->oUtilisateur,
        'titre'        => "Modifier l'utilisateur numéro $this->utilisateur_id",
        'utilisateur'  => $utilisateur,
        'erreurs'      => $erreurs
      ),
      'gabarit-admin'
    );
  }

  /**
   * Supprimer un utilisateur identifié par sa clé dans la propriété utilisateur_id
   */
  public function supprimerUtilisateur()
  {
    // if (
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR &&
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_EDITEUR
    // )
    //   throw new Exception(self::ERROR_FORBIDDEN);

    if ($this->oRequetesSQL->supprimerUtilisateur($this->utilisateur_id)) {
      $this->messageRetourAction = "Suppression de l'utilisateur numéro $this->utilisateur_id effectuée.";
    } else {
      exit();
      $this->classRetour = "erreur";
      $this->messageRetourAction = "Suppression de l'utilisateur numéro $this->utilisateur_id non effectuée.";
    }
    $this->listerUtilisateurs();
  }

  public function listerEncheres(){

      $encheres = $this->oRequetesSQL->getEncheres();
      
      (new Vue)->generer(
        'vMembreEncheres',
        array(
          'oUtilisateur'        => $this->oUtilisateur,
          'titre'               => 'Liste des Enchères',
          'encheres'        => $encheres,
          'classRetour'         => $this->classRetour,
          'messageRetourAction' => $this->messageRetourAction
        ),
        'gabarit-frontend'
      );

  }


  /* Voir si je peux importer frontend.class plutot que répeter la méthode */
  public function afficherAccueil(){
    (new Vue)->generer('vAccueil',
          [
            'titre' => 'Accueil'
          ],
          'gabarit-frontend');

  }
}
