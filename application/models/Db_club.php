<!--
Nom du fichier: Db_club.php
Auteur: Julie STEPHANT
Date de création: 27/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour les requêtes du club
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_club extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           SELECT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //"telephone" est le numero de telephone séparé par des espaces tous les 2 chiffres
	//Retourne toutes les informations du club
	public function get_info_club(){
		$query=$this->db->query("SELECT *, CONCAT_WS(' ', SUBSTRING(clu_telephone, 1, 2), SUBSTRING(clu_telephone, 3, 2), SUBSTRING(clu_telephone, 5, 2), SUBSTRING(clu_telephone, 7, 2), SUBSTRING(clu_telephone, 9, 2)) AS telephone
								FROM t_club_clu;
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           UPDATE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Modifie les informations du club
	public function update_infos_club($nom, $mail, $tel, $nom_rue, $code_postal, $ville, $pays, $categorie, $a_propos){
		$query=$this->db->query("UPDATE t_club_clu
								SET clu_nom = '".$nom."',
								clu_mail = '".$mail."',
								clu_telephone = '".$tel."',
								clu_nom_rue = '".$nom_rue."',
								clu_code_postal = '".$code_postal."',
								clu_ville = '".$ville."',
								clu_pays = '".$pays."',
								clu_categorie = '".$categorie."',
								clu_a_propos = '".$a_propos."'
								WHERE clu_id = 1;
								");
		return ($query);
	}

}