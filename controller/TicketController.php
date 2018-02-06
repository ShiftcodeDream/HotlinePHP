<?php
include "view/TicketView.php";
include "model/TicketModel.php";

// Si la personne n'est pas connectée, retour à la page d'authentification
if(is_null(getSessionValue('user_id'))){
  header('Location: index.php');
  exit;
}
  
$action = getValue('a');

switch($action){
  // Afficher le formulaire de saisie
  case 'new' : 
    ticketVueAfficheForm();
    break;
  // TODO Enregistrer un ticket
  case 'inscrit' :  
    enregistrerTicket();
    break;
  // TODO Prendre en charge un ticket
  case 'pec' : 
    if(estTechnicien()){
      priseEnChargeTicket();
    }else{
      $erreurs[] = "Vous ne pouvez prendre en charge un ticket car vous n'êtes pas technicien...";
      listeTicketsUtilisateur();
    }
  // TODO Modifier un ticket
  case 'mod' :  
    modifierTicket();
    break;
  // TODO Afficher un ticket
  case 'visu' : 
    ticketVueAfficheForm(getTicket(getValue('id')));
  // TODO Lister les tickets à traiter (technicien seulement)
  case 'vatrait' :  
    vueParDefaut();
    break;
  // TODO Lister les tickets affectés à un technicien
  case 'vtech' : 
    listeTicketsDuTechnicien();
    break;
  // TODO Lister les tickets d'un utilisateur
  case 'vuser' : 
  default :
    vueParDefaut();
    exit;
}

// Vérifie les données saisies et créé un nouveau ticket si tout est OK.
function enregistrerTicket(){
  global $erreurs, $champsErreur;
  
  $ticket = array(
    'tkt_titre' => trim(getValue('titre')),
    'tkt_description' => trim(getValue('description')),
    'tkt_urgence' => getValue('urgence'),
    'tkt_demandeur' => getSessionValue('user_id')
  );
  
  // Vérification du titre
  if(strlen($ticket['tkt_titre']) == 0){
    $erreurs[] = "Vous n'avez pas renseigné le titre";
    $champsErreur[] = "titre";
  }
  
  // Vérification de la description
  if(strlen($ticket['tkt_description']) == 0){
    $erreurs[] = "Vous n'avez pas renseigné la description";
    $champsErreur[] = "description";
  }
  
  // Vérification du degré d'urgence
  switch($ticket['tkt_urgence']){
    case '1':
    case '2':
    case '3':
    case '4':
      break;
    default:
      $ticket['tkt_urgence'] = '1';
  }
  
  // Si des champs sont en erreur
  if(!empty($champsErreur)){
    ticketVueAfficheForm($ticket);
    exit;
  }
  
  // Sinon, création du ticket
  $num = creeTicket($ticket);
  if($num > 0){
    $messages[] = "Demande n° $num créée.";
    vueParDefaut();
    exit;
  }
}

function modifierTicket(){
  // Vérifier que le ticket existe en base de données
  
  // Si non technicien, vérifier que l'utilisateur actuel est le propriétaire du ticket
  
}

function listeTicketsATraiter(){
  include_once "view/header.php";
  $liste = getGenericListeTickets('vatrait', array());
  afficheVueGeneriqueTickets($liste, array(
    // TODO 
  ));
  include_once "view/header.php";
}

function listeTicketsDuTechnicien(){
  affichageTemporaire("Lister les tickets du technicien");
}

function listeTicketsUtilisateur(){
  include_once "view/header.php";
  affichageTemporaire("Lister les tickets de l'utilisateur");
  include_once "view/header.php";
}

function priseEnChargeTicket(){
  
}

// Affiche la vue par défaut en fonction du rôle de la personne
// Sert aussi à vérifier que la personne est bien un technicien
// avant de lui afficher la vue des tickets à traiter.
function vueParDefaut(){
  if(estTechnicien())
    listeTicketsATraiter();
  else
    listeTicketsUtilisateur();
}








?>
