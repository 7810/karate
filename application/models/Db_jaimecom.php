<!--
Nom du fichier: Db_jaimecom.php
Auteur: Julie STEPHANT
Date de création: 27/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour les requêtes des j'aime et des commentaires
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_jaimecom extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           SELECT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //retourne le nombre de j'aime et de commentaire d'une publication dont on connait l'id

	public function get_count_like($id){
		$query=$this->db->query("SELECT count(*) as nb_like FROM t_aimer_aim WHERE pbl_id = ".$id.";
								");
		return $query->row();
	}

	public function get_count_comment($id){
		$query=$this->db->query("SELECT count(*) as nb_comment FROM t_commenter_com WHERE pbl_id = ".$id.";
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Permet de voir si une publication dont l'id est connu, a été aimée par l'utilisateur connecté
	public function get_like_person($pseudo, $id_publi){
		$query=$this->db->query("SELECT * FROM t_aimer_aim
								WHERE pbl_id = ".$id_publi."
								AND cpt_id = PseudoToInt('".$pseudo."');
								");
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// retourne les infos de tous les commentaires d'une publication dont on connait l'id
	public function get_all_com_publi($id_publi){
		$query=$this->db->query("SELECT *, temps_depuis_date(com_date_publi) as temps, IntToPseudo(cpt_id) as pseudo
								FROM t_commenter_com
								JOIN t_profil_pfl USING (cpt_id)
								WHERE pbl_id = ".$id_publi."
								ORDER BY com_date_publi DESC; 
								");
		return $query->result_array();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Retourne le contenu d'un commentaire dont on connait l'id, l'auteur et la publication d'origine
	public function get_commentaire($id_com, $pseudo, $id_publi){
		$query=$this->db->query(" SELECT * FROM t_commenter_com 
								WHERE com_id = ".$id_com."
								AND cpt_id = PseudoToInt('".$pseudo."')
								AND pbl_id = ".$id_publi.";
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Retourne le role du compte auteur du commentaire dont on connait l'id
	public function get_role_user_com($id){
		$query=$this->db->query(" SELECT pfl_role FROM t_profil_pfl 
								JOIN t_commenter_com USING (cpt_id)
								WHERE com_id = ".$id.";
								");
		return $query->row();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Retourne vrai si l'utilisateur $username est l'auteur du commentaire d'id $id
	public function is_user_com_author($username, $id){

		$query=$this->db->query("SELECT * FROM t_commenter_com
								WHERE com_id = '".$id."' 
								AND cpt_id = PseudoToInt('".$username."');
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Vérifie qu'un commentaire existe
	public function com_exist($id){

		$query=$this->db->query("SELECT * FROM t_commenter_com
								WHERE com_id = '".$id."';
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           INSERT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Ajoute le like de l'utilisateur connecté à une publication dont ont connait l'id
	public function insert_like($pseudo, $id_publi){
		$query=$this->db->query(" INSERT INTO `t_aimer_aim` (`cpt_id`, `pbl_id`)
								VALUES (PseudoToInt('".$pseudo."'), ".$id_publi.");
								");
		return ($query);
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Insère un nouveau commentaire de l'utilisateur connecté pour une publication dont l'id est connu
	public function insert_commentaire($pseudo, $id_publi, $contenu){
		$query=$this->db->query(" INSERT INTO `t_commenter_com` (`com_id`, `cpt_id`, `pbl_id`, `com_contenu`, `com_date_publi`)
								VALUES (NULL, PseudoToInt('".$pseudo."'), ".$id_publi.", '".$contenu."', NOW());
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           DELETE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Supprime le like de l'utilisateur connecté d'une publication dont ont connait l'id
	public function delete_like($pseudo, $id_publi){
		$query=$this->db->query(" DELETE FROM t_aimer_aim
								WHERE cpt_id = PseudoToInt('".$pseudo."')
								AND pbl_id = ".$id_publi.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Supprime un commentaire dont on connait l'id
	public function delete_commentaire($id){
		$query=$this->db->query(" DELETE FROM t_commenter_com WHERE com_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           UPDATE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Modifie les informations d'un commentaire dont on connait l'id
	public function update_commentaire($contenu, $id){
		$query=$this->db->query(" UPDATE t_commenter_com
								SET com_contenu = '".$contenu."' 
								WHERE com_id = ".$id.";
								");
		return ($query);
	}
}