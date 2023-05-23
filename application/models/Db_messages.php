<!--
Nom du fichier: Db_messages.php
Auteur: Julie STEPHANT
Date de création: 09/05/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour les requêtes des messages
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_messages extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           SELECT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Récupère la date du dernier message d'une conversation entre 2 utilisateurs
	public function get_last_date($pseudo_connecte, $pseudo_utilisateur){
		$query=$this->db->query(" SELECT mes_id, DATE_FORMAT(MAX(mes_date_envoie), '%d/%m/%Y') AS last_message FROM t_message_mes 
                                WHERE cpt_id_destinataire = PseudoToInt('".$pseudo_connecte."')
                                AND cpt_id_expediteur = PseudoToInt('".$pseudo_utilisateur."')
                                OR cpt_id_destinataire = PseudoToInt('".$pseudo_utilisateur."')
                                AND cpt_id_expediteur = PseudoToInt('".$pseudo_connecte."');
                                ");
		return $query->row();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Récupère les messages d'une conversation entre 2 utilisateurs
    public function get_messages($pseudo_connecte, $destinataire){
		$query=$this->db->query("SELECT *, IntToPseudo(cpt_id_destinataire) as pseudo_destinataire, IntToPseudo(cpt_id_expediteur) as pseudo_expediteur,
                                DATE_FORMAT(mes_date_envoie, '%H:%i') as heure_envoi,
								DATE_FORMAT(mes_date_envoie, '%d/%m/%Y') as date_envoi
                                FROM t_message_mes
                                WHERE cpt_id_destinataire = '".$destinataire."'
                                AND cpt_id_expediteur = PseudoToInt('".$pseudo_connecte."')
                                OR cpt_id_destinataire = PseudoToInt('".$pseudo_connecte."')
                                AND cpt_id_expediteur = '".$destinataire."'
                                ORDER BY mes_date_envoie;
                                ");
		return $query->result_array();
	}
 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Retourne vrai si l'utilisateur $username est l'expéditeur du message d'id $id
	public function is_user_message_expediteur($username, $id){

		$query=$this->db->query("SELECT * FROM t_message_mes
								WHERE mes_id = '".$id."' 
								AND cpt_id_expediteur = PseudoToInt('".$username."');
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Retourne le nombre de messages non lus d'une conversation
	public function get_nb_non_lus($pseudo_connecte, $id_destinataire){
		$query=$this->db->query(" SELECT count(*) as nb FROM t_message_mes 
                                WHERE mes_est_lu = 'N'
								AND cpt_id_expediteur = '".$id_destinataire."'
                                AND cpt_id_destinataire = PseudoToInt('".$pseudo_connecte."');
                                ");
		return $query->row();
	}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           INSERT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Insert le message de la personne connecté destiné au destinataire, dans la base
	public function insert_message($pseudo_connecte, $id_destinataire, $message){

		$query=$this->db->query(" INSERT INTO `t_message_mes` (`mes_id`, `mes_contenu`, `mes_date_envoie`, `mes_est_lu`, `cpt_id_destinataire`, `cpt_id_expediteur`)
								VALUES (NULL, '".$message."', NOW(), 'N', '".$id_destinataire."', PseudoToInt('".$pseudo_connecte."'));
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           DELETE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Supprime un message dont on connait l'id
	public function delete_message($id){
		$query=$this->db->query("DELETE FROM t_message_mes WHERE mes_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           UPDATE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Met à jour le status "est_lu" d'un message
	public function update_est_lu($pseudo_connecte, $id_destinataire){

		$query=$this->db->query(" UPDATE t_message_mes SET mes_est_lu = 'O' 
								WHERE cpt_id_expediteur = '" .$id_destinataire. "'
								AND cpt_id_destinataire = PseudoToInt('".$pseudo_connecte."');
								");
		return ($query);
	}

}