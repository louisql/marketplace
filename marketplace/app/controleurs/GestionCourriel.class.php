<?php

/**
 * Classe GestionCourriel
 *
 */
class GestionCourriel extends Routeur { 

  /**
   * Envoyer un courriel à l'utilisateur pour lui communiquer
   * son identifiant de connexion et son mot de passe
   * @param object $oUtilisateur utilisateur destinataire
   *
   */
  public function envoyerMdp(Utilisateur $oUtilisateur) {
    // echo ("dans envoyerMdp");
    $objetCourriel = "Envoi MDP courriel";
    $destinataire  = $oUtilisateur->utilisateur_courriel; 
    $message  = (new Vue)->generer(
      'cMdp',
      ['titre'        => 'Information',
       'http_host'    => $_SERVER['HTTP_HOST'], 
       'oUtilisateur' => $oUtilisateur],
      'gabarit-admin-min', true);

    if (ENV === "DEV") {
      $dateEnvoi = date("Y-m-d H-i-s");
      $fichier   = "mocks/courriels/$dateEnvoi-$destinataire.html";
      $nfile = fopen($fichier, "w");
      fwrite($nfile, $message);
      fclose($nfile); 
      return $fichier;
    } else {
       // ajouter les "headers" du message, puis l'envoyer par la fonction mail() 
      $headers = 'MIME-Version: 1.0' . "\n";

      $headers .= 'Content-Type: text/html; charset=utf-8' . "\n";

      $headers .= 'From: Le Méliès <support@lemelies.com>' . "\n";

      mail($destinataire, $objetCourriel, $message, $headers);
    }
  }
  

}