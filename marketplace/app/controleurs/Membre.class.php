<?php

// import Frontend.class.php from

/**
 * Classe Contrôleur des requêtes de l'application admin
 */



class Membre extends Routeur
{

  private $entite;
  private $action;
  // private $auteur_id;
  // private $livre_id;
  private $utilisateur_id;

  private $oUtilisateur;

  private $enchere_id;

  private $mise_montant;
  
  private $oEnchere;
  

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
      'l' => 'listerEncheres',
      'a' => 'ajouterEnchere',
      'm' => 'miserEnchere'
    ],
    'timbre' => [
      'l' => 'voirTimbre'
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
    $this->utilisateur_id  = $_GET['utilisateur_id']  ?? null;
    $this->enchere_id  = $_GET['enchere_id']  ?? null;
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Gérer l'interface d'administration 
   */
  public function gererMembre()
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
        $this->listerEncheres();
        exit;
      } else {
        $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
      }
    }

    (new Vue)->generer(
      'vMembreUtilisateurConnecter',
      array(
        'titre'                  => 'Connexion Membre',
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



   /**
   * Miser sur une Enchère
   */
  public function miserEnchere(){
    $mise  = []; 
    $erreurs = [];

    // $this->enchere_id = $_GET['enchere_id'];

    if (count($_POST) !== 0) {
      // retour de saisie du formulaire
      $mise = $_POST;
      // echo ('<br>');
      $oEnchere = new Enchere($mise); // création d'un objet Auteur pour contrôler la saisie
      $erreurs = $oEnchere->erreurs;


      
      if (count($erreurs) === 0) { // aucune erreur de saisie -> requête SQL d'ajout

        $this->utilisateur_id  = $_GET['utilisateur_id']  ?? null;
        $this->enchere_id  = $_GET['enchere_id']  ?? null;
        $mise_id = $this->oRequetesSQL->ajouterMise([
          'mise_montant'         => $oEnchere->miseMontant,
          'utilisateur_id'       => $this->utilisateur_id,
          'enchere_id'           => $this->enchere_id,
        ]);
        if ($mise_id > 0) { // test de la clé de la mise ajoutée
          $this->messageRetourAction = "Ajout de la mise numéro $mise_id effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout de la mise non effectué.";
        }
        $this->afficherAccueil(); // retour sur la page d'accueil
        exit;
      }
    }
    else {
      // chargement initial du formulaire
      // initialisation des champs dans la vue formulaire avec les données SQL de la mise  
      $mise  = $this->oRequetesSQL->getEnchere($this->enchere_id);
      $erreurs = [];
    }
    (new Vue)->generer(
      'vMembreEnchereMiser',
      array(
        'oEnchere'     => $this->oEnchere,
        'oUtilisateur' => $this->oUtilisateur,
        'titre'        => "Mise sur l'enchère numéro $this->enchere_id",
        'utilisateur'  => $mise,
        'enchere_id'   => $this->enchere_id,
        'erreurs'      => $erreurs
      ),
      'gabarit-frontend'
    );
  }


   /**
   * Lister les Encheres
   */
  public function listerEncheres(){
    //     if (
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_ADMINISTRATEUR &&
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_VENDEUR &&
    //   $this->oUtilisateur->utilisateur_profil !== Utilisateur::PROFIL_MEMBRE

    // )
    //   throw new Exception(self::ERROR_FORBIDDEN);

    //   exit;

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
  /**
   * Afficher les informations d'un timbre
   */
  public function voirTimbre(){

    // var_dump($this->timbre_id);
    // exit;


    $timbre = $this->oRequetesSQL->getTimbre($this->timbre_id);

    (new Vue)->generer(
      'vTimbre',
      array(
        'oUtilisateur'        => $this->oUtilisateur,
        'titre'               => 'Fiche Produit',
        // 'utilisateurs'        => $utilisateurs,
        'classRetour'         => $this->classRetour,
        'messageRetourAction' => $this->messageRetourAction,
        'timbre'              => $timbre
      ),
      'gabarit-frontend'
    );
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
    // exit;
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
   * Ajouter une enchère
   */
  public function ajouterEnchere(){
    $enchere = [];
    $erreurs = [];
    if (count($_POST) !== 0) {
      // retour de saisie du formulaire

      $enchere = $_POST;
      $oEnchere = new Enchere($enchere); // création d'un objet Auteur pour contrôler la saisie
      $erreurs = $oEnchere->erreurs;
      if (count($erreurs) === 0) { // aucune erreur de saisie -> requête SQL d'ajout
        $enchere_id = $this->oRequetesSQL->ajouterEnchere([
          'Enchere_dateDebutEnchere'          => $oEnchere->enchere_dateDebutEnchere,
          'Enchere_dateFinEnchere'            => $oEnchere->enchere_dateFinEnchere,
          'Utilisateur_id'                    => $oEnchere->utilisateur_id
        ]);


        $idDerniereEnchere = $this->oRequetesSQL->getLastEnchere();
        $idDerniereEnchere = $idDerniereEnchere[0];
        
        $maxIdEnchere = $idDerniereEnchere["MAX(idEnchere)"];
        
        $timbre_id = $this->oRequetesSQL->ajouterTimbre([
          'Enchere_nomTimbre'     => $oEnchere->enchere_nomTimbre,
          'idDerniereEnchere'     => $maxIdEnchere,
          'Utilisateur_id'        => $oEnchere->utilisateur_id,
          'dateCreationTimbre'    => $oEnchere->dateCreationTimbre,
          'paysOrigineTimbre'     => $oEnchere->paysOrigineTimbre,
          'conditionTimbre'       => $oEnchere->conditionTimbre,
          'dimensionsTimbre'      => $oEnchere->dimensionsTimbre,
          'couleurTimbre'         => $oEnchere->couleurTimbre,
          'descriptionTimbre'     => $oEnchere->descriptionTimbre,
        ]);
        $nom_fichier = $_FILES['userfile']['name'];
        $fichier = $_FILES['userfile']['tmp_name'];
        $taille = $_FILES['userfile']['size'];

        $timestamp = time();

        $url_image = "medias/img/".$timestamp.$nom_fichier; //là ou on mets dans la base de donnée -- ajouter timestamp au nom pour éviter d'avoir deux fichiers au même nom

        if(move_uploaded_file($fichier, $url_image)){
          $idDernierTimbre = $this->oRequetesSQL->getLastTimbreId();
          $idDernierTimbre = $idDernierTimbre[0];
          $maxIdTimbre = $idDernierTimbre["MAX(idTimbre)"];
          // exit();


          $upload_id = $this->oRequetesSQL->ajouterImage([
            'nomImage'  =>   $url_image,
            'Timbre_idTimbre'   => $maxIdTimbre,
          ]);
          // echo "fichier copié".$url_image;
        } else {
          // echo "fichier non copié";
          exit();
        }

        // exit;

        if ($enchere_id > 0) { // test de la clé de l'auteur ajouté
          $this->messageRetourAction = "Ajout de l'enchère numéro $enchere_id effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout de l'enchère non effectué.";
        }
        // exit();
        $this->afficherAccueil(); // retour sur la page d'accueil
        exit;
      }
    }

    (new Vue)->generer(
      'vMembreEnchereAjouter',
      array(
        'oUtilisateur' => $this->oUtilisateur,
        'titre'        => 'Mise en Vente d\'un timbre',
        'enchere'      => $enchere,
        'erreurs'      => $erreurs,
        
      ),
      'gabarit-frontend'
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




  /* Voir si je peux importer frontend.class plutot que répeter la méthode */
  public function afficherAccueil(){
    (new Vue)->generer('vAccueil',
          [
            'titre' => 'Accueil'
          ],
          'gabarit-frontend');

  }
}
