<?php
include "view/TicketView.php";
include "model/TicketModel.php";
include "model/TicketClass.php";

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
  global $erreurs, $champsErreur, $messages;
  
  $ticket = new Ticket(
    $id = getValue('id')
  );
  // S'il s'agit de la modification d'un ticket existant
  if($ticket->existe()){
    // Vérifier que l'utilisateur a bien le droit de modifier ce ticket
    if(! $ticket->verifieDroitsModif(getSessionValue('user_id'), getSessionValue('user_role'))){
      $erreurs[] = "Vous n'êtes pas le demandeur de ce ticket, vous ne pouvez pas le modifier.";
      vueParDefaut();
      return;
    }
    
    verifieChampsDeBase($ticket);


    echo '***** TODO Continuer la modif du ticket *****';
    
    
  }else{
    // Création d'un nounveau ticket 
    
  }
  
  $ticket->setTitre(htmlentities(trim(getValue('titre'))));
  $ticket->setDescription(htmlentities(trim(getValue('description'))));
  $ticket->setUrgence(getValue('urgence'));
  if(verifieChampsDeBase($ticket)){
    $ticket->setDemandeur(getSessionValue('user_id'));
    $id = $ticket->sauvegardeDonnees();
    if(!empty($id))
      $messages[] = "Demande n° " . $ticket->getId() . " soumise avec succès.";
    else
      $erreurs[] = "Une erreur s'est produite lors de la création du ticket...";
    vueParDefaut();
  }else{
    // Affichage du formulaire avec les messages d'erreurs
    ticketVueAfficheForm($ticket);
  }
}

/**
 * Vérifie les champs de base du formulaire (ceux de la demande initiale)
 * Les variables sont passées par référence car elles peuvent subir des transformations
 * @param $ticket Ticket le ticket à vérifier
 * @return boolean true si tous les champs sont OK, false sinon
 * Si des erreurs sont détectées, elles sont consignées dans les tableaux $erreurs et $champsErreur
 **/
function verifieChampsDeBase($ticket){
  global $erreurs, $champsErreur;

  // Vérification du titre
  if(strlen($ticket->getTitre()) == 0){
    $erreurs[] = "Vous n'avez pas renseigné le titre";
    $champsErreur[] = "titre";
  }
  
  // Vérification de la description
  if(strlen($ticket->getDescription()) == 0){
    $erreurs[] = "Vous n'avez pas renseigné la description";
    $champsErreur[] = "description";
  }
  
  // Vérification du degré d'urgence
  switch($ticket->getUrgence()){
    case 1:
    case 2:
    case 3:
    case 4:
      break;
    default:
      $ticket->setUrgence(1);
  }
  return empty($champsErreur);
}
  
// Demande le formulaire d'accès au ticket.
// Si le ticket existe et que la personne est autorisée à le consulter
// modifier, affiche le ticket en modification
// Si le ticket n'existe pas, afficher le formulaire vierge.
function voirModifTicket(){
  $ticket = new Ticket(getValue('id'));
  if(!$ticket->existe())
    ticketVueAfficheForm();
  
  if(!$ticket->verifieDroitsVisu(getSessionValue('user_id'), getSessionValue('user_role'))){
    $erreurs[] = "Vous ne pouvez modifier que vos propres tickets.";
    listeTicketsUtilisateur();    
  }else{
    ticketVueAfficheForm($ticket);
  }
}

function listeTicketsATraiter(){
  $liste = Ticket::getList('vatrait', null);
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
