<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO
{

  /* GESTION DES UTILISATEURS 
     ======================== */

  /**
   * Connecter un utilisateur
   * @param array $champs, tableau avec les champs utilisateur_courriel et utilisateur_mdp  
   * @return array|false ligne de la table, false sinon 
   */
  public function connecter($champs)
  {
    $this->sql = "
      SELECT idUtilisateur, nomUtilisateur, prenomUtilisateur, courrielUtilisateur, Role_idRole
      FROM utilisateur
      WHERE courrielUtilisateur = :utilisateur_courriel AND passwordUtilisateur =:utilisateur_mdp";

      // WHERE courrielUtilisateur = :utilisateur_courriel AND passwordUtilisateur = SHA2(:utilisateur_mdp, 512) REFERENCE POUR LA GESTION DU HACHAGE
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /* GESTION DES ENCHERES 
     ======================== */

     /**
   * Récupération de toutes les encheres de la table enchere
   * @return array tableau des lignes produites par la select
   */
  public function getEncheres()
  {
    $this->sql = '
    SELECT idEnchere, dateDebutEnchere, dateFinEnchere, nomUtilisateur, prenomUtilisateur, MontantMise
    FROM enchere
    INNER JOIN utilisateur ON Utilisateur_idUtilisateur = idUtilisateur
    LEFT JOIN (
        SELECT Enchere_idEnchere, MAX(MontantMise) AS MontantMise
        FROM mise
        GROUP BY Enchere_idEnchere
    ) mise ON Enchere_idEnchere = idEnchere
    ORDER BY idEnchere ASC';


    // $this->sql = '
    //   SELECT idEnchere, dateDebutEnchere, dateFinEnchere, nomUtilisateur, prenomUtilisateur FROM enchere
    //   INNER JOIN utilisateur ON Utilisateur_idUtilisateur = idUtilisateur
    //   ORDER BY idEnchere ASC';
    return $this->getLignes();
  }

    /**
   * Récupération d'un utilisateur de la table enchere
   * @param int $enchere_id 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getEnchere($enchere_id)
  {
    $this->sql = '
    SELECT idEnchere
      FROM enchere WHERE idEnchere = :enchere_id';
    return $this->getLignes(['enchere_id' => $enchere_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  
  /**
   * Ajouter une enchère
   * @param array $champs tableau des champs de l'enchère 
   * @return string|boolean clé primaire de la ligne ajoutée, false sinon
   */
  public function ajouterEnchere($champs)
  {
    // var_dump($champs);
    $this->sql = '
      INSERT INTO enchere SET 
      dateDebutEnchere = :Enchere_dateDebutEnchere, dateFinEnchere = :Enchere_dateFinEnchere, Utilisateur_idUtilisateur = :Utilisateur_id
      ';
    return $this->CUDLigne($champs);
  }

  /**
   * Récupérer la primary key de la dernière enchère ajoutée
   * @return string valeur de la primary key 
   */

  public function getLastEnchere(){
    $this->sql = '
    SELECT MAX(idEnchere) FROM enchere
    ';

    return $this->getLignes();
  }

  /**
   * Récupérer la primary key du dernier timbre ajoutée
   * @return string valeur de la primary key 
   */

  public function getLastTimbreId(){
    $this->sql = '
    SELECT MAX(idTimbre) FROM timbre
    ';

    return $this->getLignes();
  }

  /**
   * Ajouter un timbre
   * @param array $champs tableau des champs du timbre 
   * @return string|boolean clé primaire de la ligne ajoutée, false sinon
   */
  public function ajouterTimbre($champs)
  {
    // var_dump($champs);
    $this->sql = '
      INSERT INTO timbre SET 
      nomTimbre = :Enchere_nomTimbre, Enchere_idEnchere = :idDerniereEnchere, Utilisateur_idUtilisateur = :Utilisateur_id, 
      dateCreationTimbre = :dateCreationTimbre, paysOrigineTimbre = :paysOrigineTimbre, conditionTimbre = :conditionTimbre,
      dimensionsTimbre = :dimensionsTimbre, couleurTimbre = :couleurTimbre, descriptionTimbre = :descriptionTimbre 
      ';
    return $this->CUDLigne($champs);
  }

  /**
   * Ajouter une image
   * @param array $champs tableau des champs du timbre 
   * @return string|boolean clé primaire de la ligne ajoutée, false sinon
   */
  public function ajouterImage($champs)
  {
    // var_dump($champs);
    // exit();
    $this->sql = '
      INSERT INTO image SET 
      nomImage = :nomImage, Timbre_idTimbre = :Timbre_idTimbre
      ';
    return $this->CUDLigne($champs);
  }


  /* GESTION DES MSES 
     ======================== */

    /**
     * 
   * Ajouter une mise
   * @param array $champs tableau des champs de la mise 
   * @return string|boolean clé primaire de la ligne ajoutée, false sinon
   */
  public function ajouterMise($champs)
  {
    $this->sql = '
      INSERT INTO mise SET 
      montantMise = :mise_montant, Utilisateur_idUtilisateur = :utilisateur_id, Enchere_idEnchere = :enchere_id
      ';
    return $this->CUDLigne($champs);
  }


  /* GESTION DES UTILISATEURS 
     ======================== */

  /**
   * Récupération de tous les utilisateurs de la table utilisateur
   * @return array tableau des lignes produites par la select
   */
  public function getUtilisateurs()
  {
    $this->sql = '
      SELECT idUtilisateur, nomUtilisateur, prenomUtilisateur, courrielUtilisateur, passwordUtilisateur, Role_idRole, nomRole FROM utilisateur
      INNER JOIN role ON Role_idRole = idRole
      ORDER BY idUtilisateur DESC';
    return $this->getLignes();
  }

  /**
   * Récupération d'un utilisateur de la table utilisateur
   * @param int $utilisateur_id 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne
   */
  public function getUtilisateur($utilisateur_id)
  {
    $this->sql = '
    SELECT idUtilisateur, nomUtilisateur, prenomUtilisateur, courrielUtilisateur, passwordUtilisateur, Role_idRole
      FROM utilisateur WHERE idUtilisateur = :utilisateur_id';
    return $this->getLignes(['utilisateur_id' => $utilisateur_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un utilisateur
   * @param array $champs tableau des champs de l'auteur 
   * @return string|boolean clé primaire de la ligne ajoutée, false sinon
   */
  public function ajouterUtilisateur($champs)
  {
    $this->sql = '
      INSERT INTO utilisateur SET 
      nomUtilisateur = :utilisateur_nom, prenomUtilisateur = :utilisateur_prenom, courrielUtilisateur = :utilisateur_courriel, passwordUtilisateur = :utilisateur_password, Role_idRole = :utilisateur_profil
      ';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier un utilisateur
   * @param array $champs tableau avec les champs à modifier et la clé utilisateur_id
   * @return boolean true si modification effectuée, false sinon
   */
  public function modifierUtilisateur($champs)
  {
    $this->sql = '
      UPDATE utilisateur SET nomUtilisateur = :utilisateur_nom, prenomUtilisateur = :utilisateur_prenom, courrielUtilisateur = :utilisateur_courriel, passwordUtilisateur = :utilisateur_password, Role_idRole = :utilisateur_profil
      WHERE idUtilisateur = :utilisateur_id';
      return $this->CUDLigne($champs);
      
  }

  /**
   * Supprimer un utilisateur
   * @param int $utilisateur_id clé primaire
   * @return boolean true si suppression effectuée, false sinon
   */
  public function supprimerUtilisateur($utilisateur_id)
  {
    $this->sql = '
      DELETE FROM utilisateur WHERE idUtilisateur = :utilisateur_id';
    /* AND utilisateur_id NOT IN (SELECT DISTINCT livre_utilisateur_id FROM livre);  */ // pour éviter une exception PDO s'il existe des livres rattachés à cet utilisateur
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }

    
/* --------------GESTION DES TIMBRES------------------- */
  /**
  * Récupération de tous les utilisateurs de la table utilisateur
  * @return array tableau des lignes produites par la select
  */

  public function getTimbres()
  {
    $this->sql = '
    SELECT *
    FROM timbre t
    INNER JOIN image i ON i.Timbre_idTimbre = t.idTimbre
    INNER JOIN enchere e ON t.Enchere_idEnchere = e.idEnchere
    LEFT JOIN (
      SELECT Enchere_idEnchere, MAX(MontantMise) AS MontantMiseMax
      FROM mise m
      GROUP BY Enchere_idEnchere
    ) m ON m.Enchere_idEnchere = e.idEnchere
      ORDER BY idTimbre ASC';
    return $this->getLignes();
  }

  public function getTimbre($idTimbre){
    $this->sql="
    SELECT *
    FROM timbre t
    INNER JOIN image i ON i.Timbre_idTimbre = t.idTimbre
    INNER JOIN enchere e ON t.Enchere_idEnchere = e.idEnchere
    LEFT JOIN (
      SELECT Enchere_idEnchere, MAX(MontantMise) AS MontantMiseMax
      FROM mise m
      GROUP BY Enchere_idEnchere
    ) m ON m.Enchere_idEnchere = e.idEnchere
    WHERE t.idTimbre = '$idTimbre'";
    
    return $this->getLignes();
    }

  public function search($inputText){
    $this->sql="
      SELECT * FROM timbre
      WHERE '$inputText' LIKE
    ";
  }


  public function generer_mdpSQL($utilisateur_id, $mdp){
    $this->sql = "
    UPDATE utilisateur
    SET utilisateur_mdp = SHA2('$mdp', 512)
    WHERE utilisateur_id = :utilisateur_id
    ";

    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }

}
