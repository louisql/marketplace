<?php
require_once('app/includes/config.php');
require 'app/includes/chargementClasses.inc.php';

// Après le script de chargement des classes
// pour pouvoir recharger l'objet de la classe Utilisateur
// stocké dans $_SESSION si l'utilisateur est connecté
session_start(); 

new Routeur;

/* pour deconnecter utilisateur non autorisé http://localhost:3000/admin?entite=utilisateur&action=d */