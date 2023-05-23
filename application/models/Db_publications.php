<!--
Nom du fichier: Db_publications.php
Auteur: Julie STEPHANT
Date de création: 27/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour les requêtes des publications
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_publications extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           SELECT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // retourne les infos de toutes les publications de la date de publication la plus récente à la plus ancienne
	//DATE_FORMAT permet de formater une date (et/ou une heure si DATETIME) selon le format précisé
	public function get_all_publi(/*$limit, $offset*/){
		$query=$this->db->query("SELECT pbl_id, pbl_etat, pbl_img, pbl_description, DATE_FORMAT(pbl_date_publi, '%d/%m/%Y') as date_publi,
								DATE_FORMAT(pbl_date_publi, '%H:%i') as heure_publi, IntToPseudo(cpt_id) as pseudo 
								FROM t_publication_pbl
								ORDER BY pbl_date_publi DESC;
								"); //LIMIT $offset, $limit;
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne une seule publication dont on connait l'id
	public function get_one_publi($id){
		$query=$this->db->query(" SELECT pbl_id, pbl_etat, pbl_img, pbl_description, DATE_FORMAT(pbl_date_publi, '%d/%m/%Y') as date_publi,
								DATE_FORMAT(pbl_date_publi, '%H:%i') as heure_publi, IntToPseudo(cpt_id) as pseudo 
								FROM t_publication_pbl
								WHERE pbl_id = '".$id."';
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// retourne les infos de toutes les publications publiques de la date de publication la plus récente à la plus ancienne
	public function get_all_publi_publiques(){
		$query=$this->db->query("SELECT pbl_id, pbl_img, pbl_description, DATE_FORMAT(pbl_date_publi, '%d/%m/%Y') as date_publi,
								DATE_FORMAT(pbl_date_publi, '%H:%i') as heure_publi, IntToPseudo(cpt_id) as pseudo 
								FROM t_publication_pbl
								WHERE pbl_etat = 'P'
								ORDER BY pbl_date_publi DESC;
								");
		return $query->result_array();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne l'image d'une publication dont on connait l'id
	public function get_img_publi($id){
		$query=$this->db->query(" SELECT pbl_img
								FROM t_publication_pbl
								WHERE pbl_id = '".$id."';
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Retourne vrai si l'utilisateur $username est l'auteur de la publication d'id $id
	public function is_user_publi_author($username, $id){

		$query=$this->db->query("SELECT * FROM t_publication_pbl
								WHERE pbl_id = '".$id."' 
								AND cpt_id = PseudoToInt('".$username."');
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Vérifie qu'une publication existe
	public function publi_exist($id){

		$query=$this->db->query("SELECT * FROM t_publication_pbl
								WHERE pbl_id = '".$id."';
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           DELETE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Supprime une publication dont on connait l'id
	// (Tous les likes et commentaires seront supprimés grâce à un trigger)
	public function delete_publi($id){
		$query=$this->db->query("DELETE FROM t_publication_pbl WHERE pbl_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           UPDATE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Modifie la description d'une publication dont on connait l'id
	public function update_publication($description, $etat, $id){
		$query=$this->db->query(" UPDATE t_publication_pbl
								SET pbl_description = '".$description."',
								pbl_etat = '".$etat."' 
								WHERE pbl_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           INSERT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// insert une nouvelle publication de l'utilisateur connecté
	public function insert_publication($pseudo, $description, $img, $etat){
		if($img == null){ //Si la publiaction n'a pas d'image
			$query=$this->db->query(" INSERT INTO `t_publication_pbl` (`pbl_id`, `pbl_img`, `pbl_description`, `pbl_date_publi`, `pbl_etat`, `cpt_id`)
								VALUES (NULL, NULL, '".$description."', NOW(), '".$etat."', PseudoToInt('".$pseudo."'));
								");
		}else{
			$query=$this->db->query(" INSERT INTO `t_publication_pbl` (`pbl_id`, `pbl_img`, `pbl_description`, `pbl_date_publi`, `pbl_etat`, `cpt_id`)
									VALUES (NULL, '".$img."', '".$description."', NOW(), '".$etat."', PseudoToInt('".$pseudo."'));
									");
		}
		return ($query);
	}

}