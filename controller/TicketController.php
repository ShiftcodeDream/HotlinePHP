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
  // Enregistrer un ticket
  case 'inscrit' :  
  case 'mod' :  
    enregistrerTicket();
    break;
  // Prendre en charge un ticket
  case 'pec' : 
    actionPECTicket();
    break;
  // Afficher un ticket
  case 'visu' : 
    voirModifTicket();
    break;
  // Lister les tickets à traiter (technicien seulement)
  case 'vatrait' :  
    vueParDefaut();
    break;
  // TODO Lister les tickets affectés à un technicien
  case 'vtech' : 
    listeTicketsDuTechnicien();
    break;
  // TODO Clore le ticket
  case 'clore' :
    cloreTicket();
  // TODO Lister les tickets d'un utilisateur
  case 'vuser' : 
  default :
    vueParDefaut();
    exit;
}

// TODO Clore le ticket
function cloreTicket(){
  
}

/**
 * Effectue l'ajout et la mise à jour d'un ticket
 * en ayant pris soin de vérifier les éléments saisis
 **/
function enregistrerTicket(){
  global $erreurs, $champsErreur;
  
  $ticket = array(
    'tkt_id' => getValue('id'),
    'tkt_titre' => trim(getValue('titre')),
    'tkt_description' => trim(getValue('description')),
    'tkt_urgence' => getValue('urgence'),
    'tkt_demandeur' => getSessionValue('user_id')
  );
  
  // Vérifier que la personne a le droit de modifier le ticket
  if(!verifieDroitsTicket($ticket)){
    $erreurs[] = "Vous ne pouvez modifier que vos propres tickets";
    vueParDefaut();
    return;
  }
  
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
  
  
  
  // Si tout est OK, création / modification du ticket
  if(isset($ticket['tkt_id'])){
    modifieTicket($ticket);
    $messages[] = "Ticket modifié avec succès";
    vueParDefaut();
  }else{
    $num = creeTicket($ticket);
    if($num > 0){
      $messages[] = "Demande n° $num créée.";
      vueParDefaut();
    }
  }
}

// Demande le formulaire d'accès au ticket.
// Si le ticket existe et que la personne est autorisée à le consulter
// modifier, affiche le ticket en modification
// Si le ticket n'existe pas, afficher le formulaire vierge.
function voirModifTicket(){
  $id = getValue('id');
  // Pour les nouveaux tickets
  $ticket = getTicket($id);
  if(is_null($ticket)){
    ticketVueAfficheForm();
  }
  if(!verifieDroitsTicket($ticket)){
    $erreurs[] = "Vous ne pouvez modifier que vos propres tickets.";
    listeTicketsUtilisateur();    
  }else{
    ticketVueAfficheForm($ticket);
  }
}

/**
 * Vérifie que la personne a bien le droit d'accéder au ticket
 @return boolean true si l'utilisateur a le droit d'accéder au ticket
 **/
function verifieDroitsTicket($ticket){
  // L'utilisateur ne peut consulter / modifier que ses propres tickets
  return estTechnicien() || $ticket['tkt_demandeur'] == getSessionValue('user_id');
}

function listeTicketsATraiter(){
  $liste = getGenericListeTickets('vatrait', null);
  afficheListeTicketsATraiter($liste);
}

function listeTicketsDuTechnicien(){
  affichageTemporaire("Lister les tickets du technicien");
}

function listeTicketsUtilisateur(){
  include_once "view/header.php";
  affichageTemporaire("Lister les tickets de l'utilisateur");
  include_once "view/header.php";
}

function actionPECTicket(){
  global $erreurs, $messages;
  if(!estTechnicien()){
    $erreurs[] = "Vous ne pouvez prendre en charge un ticket car vous n'êtes pas technicien...";
    listeTicketsUtilisateur();
  }else{
    $id = getValue('id');
    switch(prendEnChargeTicket($id, getSessionValue('user_id'))){
      case -1 : 
        $erreurs[] = "Ticket $id non trouvé";
        vueParDefaut();
        break;
      case -2 :
        $messages[] = "Ce ticket a déjà été pris en compte";
        voirModifTicket();
        break;
      default :     
        $messages[]= "Ticket $id pris en charge.";
        voirModifTicket();
    }
  }
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
