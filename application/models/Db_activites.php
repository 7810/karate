<!--
Nom du fichier: Db_activites.php
Auteur: Julie STEPHANT
Date de création: 27/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour les requêtes des activites
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_activites extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           SELECT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /*CONCAT(jour_fr(DAYNAME(act_date_debut)), ' ', DATE_FORMAT(act_date_debut, '%d'), ' ', mois_fr(MONTHNAME(act_date_debut)), ' ', DATE_FORMAT(act_date_debut, '%Y'))
	permet de formater la date de début d'une activité comme suit: Vendredi 12 mai 2023*/
	//Retourne toutes les informations des activités
	public function get_all_activites(){
		$query=$this->db->query("SELECT *,
								CONCAT(jour_fr(DAYNAME(act_date_debut)), ' ', DATE_FORMAT(act_date_debut, '%d'), ' ', mois_fr(MONTHNAME(act_date_debut)), ' ', DATE_FORMAT(act_date_debut, '%Y')) as date_debut,
								DATE_FORMAT(act_date_debut, '%H:%i') as heure_debut,
								CONCAT(jour_fr(DAYNAME(act_date_fin)), ' ', DATE_FORMAT(act_date_fin, '%d'), ' ', mois_fr(MONTHNAME(act_date_fin)), ' ', DATE_FORMAT(act_date_fin, '%Y')) as date_fin,
								DATE_FORMAT(act_date_fin, '%H:%i') as heure_fin,
								IntToPseudo(cpt_id) as pseudo
								FROM t_activite_act
								ORDER BY act_date_debut;
								");
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne le nombre d'activité actives en cours, le nombre d'activité (annulée ou non) à venir et le nombre d'activité actives passées
	public function get_nb_status_act(){
		$query=$this->db->query("CALL status_act();");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne le nombre d'activité en cours, le nombre d'activité à venir et le nombre d'activité passées
	public function get_nb_status_act_admin_prof(){
		$query=$this->db->query("CALL status_act_admin_prof();");
		return $query->row();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne les infos d'une activité dont on connait l'id
	public function get_activite($id){
		$query=$this->db->query(" SELECT *,
								CONCAT(jour_fr(DAYNAME(act_date_debut)), ' ', DATE_FORMAT(act_date_debut, '%d'), ' ', mois_fr(MONTHNAME(act_date_debut)), ' ', DATE_FORMAT(act_date_debut, '%Y')) as date_debut,
								DATE_FORMAT(act_date_debut, '%H:%i') as heure_debut,
								CONCAT(jour_fr(DAYNAME(act_date_fin)), ' ', DATE_FORMAT(act_date_fin, '%d'), ' ', mois_fr(MONTHNAME(act_date_fin)), ' ', DATE_FORMAT(act_date_fin, '%Y')) as date_fin,
								DATE_FORMAT(act_date_fin, '%H:%i') as heure_fin,
								IntToPseudo(cpt_id) as pseudo
								FROM t_activite_act
								WHERE act_id = ".$id.";
								");
		return $query->row();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne true si l'utilisateur $username est l'auteur de l'activité d'id $id
	public function is_user_activite_author($username, $id){

		$query=$this->db->query("SELECT * FROM t_activite_act
								WHERE act_id = '".$id."' 
								AND cpt_id = PseudoToInt('".$username."');
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           UPDATE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Active ou annule une activité dont on connait l'id selon son état actuel
	public function update_etat_activite($id){
		$query=$this->db->query("CALL activer_annuler('".$id."');");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Modifie les informations d'une activité dont on connait l'id
	public function update_activite($id, $intitule, $description, $lieu, $date_debut, $date_fin, $auteur){
		$query=$this->db->query("UPDATE t_activite_act
								SET act_intitule = '".$intitule."',
								act_description = '".$description."',
								act_lieu = '".$lieu."',
								act_date_debut = '".$date_debut."',
								act_date_fin = '".$date_fin."', 
								cpt_id = PseudoToInt('".$auteur."')
								WHERE act_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           INSERT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Insert une nouvelle activité
	public function insert_activite($intitule, $description, $lieu, $date_debut, $date_fin, $pseudo){
		$query=$this->db->query("INSERT INTO `t_activite_act` (`act_id`, `act_intitule`, `act_description`, `act_date_debut`, `act_date_fin`, `act_lieu`, `act_etat`, `cpt_id`)
								VALUES (NULL, '".$intitule."', '".$description."', '".$date_debut."', '".$date_fin."', '".$lieu."', 'A', PseudoToInt('".$pseudo."'));
								");
		return ($query);
	}

}