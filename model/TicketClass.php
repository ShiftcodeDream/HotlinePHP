<?php
include_once "utils/db.php";
/** Représente une demande utilisateur
 *
**/

class Ticket{
  
  // Etats du ticket
  const SOUMIS = 0;
  const PRIS_EN_CHARGE = 1;
  const RESOLU = 2;
    
  protected $id = 0;
  protected $titre ='';
  protected $description = '';
  protected $solution = '';
  protected $urgence = 1;
  protected $impact = 1;
  protected $demandeur = 0;
  protected $technicien = 0;
  protected $etat = 0;
  protected $temps_passe = 0;
  protected $date_demande = null;
  protected $date_pec = null;
  protected $date_solution = null;
  protected $demandeur_nom = '';
  protected $technicien_nom = '';

  // Requetes utilisées par les différentes listes
  protected static $requetes = [
    'vuser'   => 'SELECT * FROM TicketAll WHERE tkt_demandeur = :user_id',
    'vatrait' => 'SELECT * FROM TicketAll WHERE tkt_etat < 2',
    'vtech'   => 'SELECT * FROM TicketAll WHERE tkt_technicien = :user_id',
		'vall'    => 'SELECT * FROM TicketAll'
  ];
  /** Le constructeur. Si l'id est renseigné et non égal à 0,
   * remplit les attributs de la classe avec les valeurs trouvées en base de données
   **/
  public function __construct($id = null){
    $this->id = $id;
    if(!empty($id)){
      $this->chargeDonnees();
    }
  }
  /** Charge les données depuis la base de données
  **/
  protected function chargeDonnees(){
    $t = dbGetOne(
      'SELECT * FROM TicketAll WHERE tkt_id = :id',
      array('id' => $this->id)
    );
    if(!is_null($t)){
      $this->titre          = $t['tkt_titre'];
      $this->description    = $t['tkt_description'];
      $this->solution       = $t['tkt_solution'];
      $this->urgence        = $t['tkt_urgence'];
      $this->impact         = $t['tkt_impact'];
      $this->demandeur      = $t['tkt_demandeur'];
      $this->technicien     = $t['tkt_technicien'];
      $this->etat           = $t['tkt_etat'];
      $this->temps_passe    = $t['tkt_temps_passe'];
      $this->date_demande   = $t['tkt_date_demande'];
      $this->date_pec       = $t['tkt_date_pec'];
      $this->date_solution  = $t['tkt_date_solution'];
      $this->demandeur_nom  = $t['tkt_demandeur_nom'];
      $this->technicien_nom = $t['tkt_technicien_nom'];
    }else{
      $this->id = 0;
    }
  }
  /** Enregistre en base de données les informations de l'objet courant
   * @return int Renvoie l'id de l'élément créé / sauvegardé en cas de succès
   * et false sinon.
  **/
  public function sauvegardeDonnees(){
    // Cas d'un nouveau ticket
    if(empty($this->id)){
      $this->id = dbInsert(
        'INSERT INTO Ticket
        (tkt_titre, tkt_description, tkt_urgence,
        tkt_demandeur, tkt_etat, tkt_date_demande)
        VALUES(:titre, :description, :urgence, :demandeur,
        0, now())',
        array(
          'titre'       => $this->titre,
          'description' => $this->description,
          'urgence'     => $this->urgence,
          'demandeur'   => $this->demandeur
        )
      );
      // Si l'insertion a réussi, on recharge la classe avec les données réellement
      // présentes dans la base de données
      if($this->id){
        $this->chargeDonnees();
      }
    }
    // Mise à jour d'un ticket existant
    else{
      if(empty($this->temps_passe))
        $this->temps_passe=0;
      dbExecute(
        'UPDATE Ticket SET 
          tkt_titre = :titre,
          tkt_description = :description,
          tkt_solution = :solution,
          tkt_urgence = :urgence,
          tkt_impact = :impact,
          tkt_demandeur = :demandeur,
          tkt_technicien = :technicien,
          tkt_temps_passe = :temps_passe
        WHERE tkt_id = :id',     
        array(
          'titre'       => $this->titre,
          'description' => $this->description,
          'solution'    => $this->solution,
          'urgence'     => $this->urgence,
          'impact'      => $this->impact,
          'demandeur'   => $this->demandeur,
          'technicien'  => $this->technicien,
          'temps_passe' => $this->temps_passe,
          'id'          => $this->id
        )
      );
      // Recharge les données avec les nouvelles valeurs présentes en base de données
      $this->chargeDonnees();
    }
    return $this->id;
  }
  public function getId(){
    return $this->id;
  } // Pas de setter pour l'id, c'est la classe qui gère son propre id
  public function getTitre(){
    return $this->titre;
  }
  public function setTitre($titre){
    $this->titre = $titre;
  }
  public function getDescription(){
    return $this->description;
  }
  public function setDescription($description){
    $this->description = $description;
  }
  public function getSolution(){
    return $this->solution;
  }
  public function setSolution($solution){
    $this->solution = $solution;
  }
  public function getUrgence(){
    return $this->urgence;
  }
  public function setUrgence($urgence){
    $this->urgence = $urgence;
  }
  public function getImpact(){
    return $this->impact;
  }
  public function setImpact($impact){
    $this->impact = $impact;
  }
  public function getDemandeur(){
    return $this->demandeur;
  }
  public function setDemandeur($demandeur){
    $this->demandeur = $demandeur;
    $this->demandeur_nom = dbGetOne(
      "SELECT CONCAT(usr_prenom, ' ', usr_nom) as nom
      FROM Utilisateur WHERE usr_id = :id",
      array('id' => $demandeur)
    )['nom'];
  }
  public function getTechnicien(){
    return $this->technicien;
  }
  public function setTechnicien($technicien){
    $this->technicien = $technicien;
    $this->technicien_nom = dbGetOne(
      "SELECT CONCAT(usr_prenom, ' ', usr_nom) as nom
      FROM Utilisateur WHERE usr_id = :id",
      array('id' => $technicien)
    )['nom'];
  }
  public function getEtat(){
    return $this->etat;
  }
  public function getTempsPasse(){
    return $this->temps_passe;
  }
  public function setTempsPasse($temps_passe){
    $this->temps_passe = $temps_passe;
  }
  public function getDateDemande(){
    return $this->date_demande;
  }
  public function getDatePec(){
    return $this->date_pec;
  }
  public function getDateSolution(){
    return $this->date_solution;
  }
  public function getDemandeurNom(){
    return $this->demandeur_nom;
  }
  public function getTechnicienNom(){
    return $this->technicien_nom;
  }
  /**
   * Effectue la prise en charge du ticket
   * @param $technicien int Identifiant du technicien
   * @return boolean true si le ticket a pu être pris en charge, false sinon.
   **/
  public function prendreEnCharge($technicien){
    global $erreurs;
    if(!empty($this->id) && ($this->etat == self::SOUMIS)){
      dbExecute(
        'UPDATE Ticket SET tkt_etat = ' . self::PRIS_EN_CHARGE .',
        tkt_date_pec = now(), tkt_technicien = :technicien
        WHERE tkt_id = :id',
        array(
          'technicien' => (int)$technicien,
          'id' => (int)$this->id
        ));
      $this->chargeDonnees();
    }
    return ($this->etat == self::PRIS_EN_CHARGE);
  }
  /** 
   * Effectue la cliture d'une demande
   * Le ticket doit être affecté, et les éléments temps passé et solution doivent
   * avoir été renseignés.
   * @return boolean true si la cloture de la demande a été effectuée, false sinon.
   **/
  public function clore(){
    global $erreurs;
    // Enregistre toutes les valeurs modifiées dans la base avant de valider le changement
    // d'état du ticket
    $this->sauvegardeDonnees();
    if(!empty($this->id)){
      switch($this->etat){
        case self::SOUMIS :
          $erreurs[] = "La demande doit d'abbord être prise en charge avant de pouvoir être clôturée.";
          return false;
        case self::PRIS_EN_CHARGE :
					if(empty($this->temps_passe))
						$erreurs[] = "Le temps passé n'est pas renseigné";
					if(strlen(trim($this->solution)) ==0)
						$erreurs[] = "Pour clore un ticket, il faut lui donner une solution";
					if(!empty($erreurs))
						return false;
          dbExecute(
            'UPDATE Ticket SET tkt_etat = ' . self::RESOLU. ',
            tkt_date_solution = now()
            WHERE tkt_id = :id',
            array('id' => $this->id));
          $this->chargeDonnees();
          return ($this->etat == self::RESOLU);
        case self::RESOLU :
          $erreurs[] = "La demande est déjà clôturée";
          return false;
      }
    }
    return ($this->etat == self::RESOLU);
  }
  
  /**
   * Renvoie la liste des tickets, selon le modèle de critères demandé
   * @param nom_liste string nom de la requete à appeler
   * @param params array tableau de paramètres pour la requete sélectionnee
   **/
  public static function getList($nom_liste, $params = null){
    if(!array_key_exists($nom_liste, self::$requetes))
      return array();
    return dbSelect(self::$requetes[$nom_liste], $params);
  }
  
  /** 
   * @param user_id int identifiant de la personne voulant effectuer des modifications
   * @param user_role string role de la personne
   * @return boolean true si la personne a le droit de modifier le ticket
   **/
  public function verifieDroitsModif($user_id, $user_role){
    // Ticket non créé
    if(empty($this->id))
      return true;
    // Ticket existant
    switch($this->etat){
      case self::SOUMIS :
        // L'utilisateur peut modifier sa demande tant qu'elle n'a pas été prise en charge
        return ($this->demandeur == $user_id);
      case self::PRIS_EN_CHARGE :
        // Seul le technicien en charge de la demande peut modifier la demande
        return (($this->technicien == $user_id) && ($user_role == 'tech'));
      // Une fois résolu, plus personne ne peut modifier un ticket.
			case self::RESOLU :
				return false;
    }
  }

  /** 
   * @param user_id int identifiant de la personne voulant effectuer des modifications
   * @param user_role string role de la personne
   * @return boolean true si la personne a le droit de visualiser le ticket
   **/
  public function verifieDroitsVisu($user_id, $user_role){
    // Ticket non créé
    if(empty($this->id))
      return true;
    // Ticket existant
    // Le technicien peut voir tous les tickets
    if($user_role === 'tech')
      return true;
    // L'utilisateur ne peut voir que ses propres tickets
    return ($this->demandeur == $user_id);
  }
  /**
   * @return boolean true si le ticket existe, false s'il n'est pas encore créé
   **/
  public function existe(){
    return !empty($this->id);
  }
  /**
   * @return boolean true si le ticket a été pris soumis mais n'est encore ni pris en compte ni résolu
   **/
  public function estSoumis(){
    return $this->existe() && $this->etat == self::SOUMIS;
  }
  /**
   * @return boolean true si le ticket a été pris en compte mais pas encore résolu
   **/
  public function estPrisEnCharge(){
    return $this->existe() && $this->etat == self::PRIS_EN_CHARGE;
  }
  /**
   * @return true si le ticket est résolu, false sinon
   **/
  public function estResolu(){
    return $this->existe() && $this->etat == self::RESOLU;
  }
  /**
   * @return string libellé d'un état d'une demande
   **/
  public static function getLibelleEtat($etat){
		switch($etat){
			case self::SOUMIS :
				return 'soumis';
			case self::PRIS_EN_CHARGE :
				return 'pris en charge';
			case self::RESOLU :
				return 'résolu';
			default :
				return 'non créé';
		}
  }
	
  /**
   * @param $urgence int degré d'urgence
   * @return string Libellé de l'urgence
   **/
  public static function getLibelleUrgence($urgence){
  	switch($urgence){
			case 1 :
				return 'pas du tout';
			case 2 :
				return 'un peu';
			case 3 : 
				return 'moyenne';
			case 4 :
				return 'élevée';
			default :
				return 'inconnue';
		}
  }
}
?>
