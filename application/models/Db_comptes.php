<!--
Nom du fichier: Db_comptes.php
Auteur: Julie STEPHANT
Date de création: 27/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour les requêtes des comptes
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_comptes extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	//pour saler et hacher le mot de passe à l'installation de la bdd
	/*public function saler_hacher($id, $password){

		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$mdp = hash('sha256', $salt.$password);

		$query=$this->db->query("UPDATE t_compte_cpt SET cpt_mdp = '".$mdp."' WHERE cpt_id = '".$id."'");
		return ($query);
	}*/

	/* copier coller dans controller accueil
	$this->db_comptes->saler_hacher(1, "Wado_ryu_queven56");
    $this->db_comptes->saler_hacher(2, "Pascal#56");
    $this->db_comptes->saler_hacher(3, "Martin&56");
    $this->db_comptes->saler_hacher(4, "Ana#2956");
    $this->db_comptes->saler_hacher(5, "Ila56nn");
    $this->db_comptes->saler_hacher(6, "Ju56ju56");
    $this->db_comptes->saler_hacher(7, "Clem809");
    $this->db_comptes->saler_hacher(8, "Maxou78");*/
	
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           SELECT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// retourne le mail du president
	public function get_mail_president(){
		$query=$this->db->query("SELECT pfl_mail FROM t_profil_pfl
								WHERE pfl_role='D';
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne le role d'un compte dont on connait le pseudo
	public function get_role($username){
		$query=$this->db->query("SELECT pfl_role FROM t_profil_pfl
								JOIN t_compte_cpt USING(cpt_id)
								WHERE cpt_pseudo = '".$username."';
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Regarde si le login et le mdp existent dans la base en tant que compte activé
	//retourne true ou false en conséquence
	public function connect($username, $mdp){

		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$password = hash('sha256', $salt.$mdp);

		$query=$this->db->query("SELECT cpt_pseudo, cpt_mdp FROM t_compte_cpt 
								JOIN t_profil_pfl USING(cpt_id)
								WHERE cpt_pseudo='".$username."' AND cpt_mdp='".$password."' AND pfl_etat='A';
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Regarde si le login et le mdp existent dans la base
	//retourne true ou false en conséquence
	public function bon_mdp($username, $mdp){

		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$password = hash('sha256', $salt.$mdp);

		$query=$this->db->query("SELECT cpt_pseudo, cpt_mdp FROM t_compte_cpt 
								JOIN t_profil_pfl USING(cpt_id)
								WHERE cpt_pseudo='".$username."' AND cpt_mdp='".$password."';
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Récupère toutes les informations de tous les profils
	public function get_all_comptes(){
		$query=$this->db->query("SELECT *, IntToPseudo(cpt_id) as pseudo FROM t_profil_pfl
								ORDER BY 
								CASE pfl_role
									WHEN 'D' THEN 1
									WHEN 'A' THEN 2
									WHEN 'P' THEN 3
									WHEN 'M' THEN 4
									ELSE 5
								END;
								");
		return $query->result_array();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//retourne toutes les informations d'un compte dont on connait le pseudo
	public function get_all_info_profil($username){
		$query=$this->db->query(" SELECT * FROM t_compte_cpt
								JOIN t_profil_pfl USING (cpt_id)
								WHERE cpt_pseudo = '".$username."';
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//retourne le pseudo, la pp et l'id d'un compte dont on connait l'id
	public function get_pseudo_by_id($id){
		$query=$this->db->query(" SELECT cpt_id, cpt_pseudo, pfl_pp FROM t_compte_cpt
								JOIN t_profil_pfl USING(cpt_id)
								WHERE cpt_id = ".$id.";
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//retourne l'id d'un compte dont on connait le pseudo
	public function get_id_by_pseudo($pseudo){
		$query=$this->db->query(" SELECT cpt_id FROM t_compte_cpt
								WHERE cpt_pseudo = '".$pseudo."';
								");
		return $query->row();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function get_count_comptes(){
		$query=$this->db->query("SELECT COUNT(*) as nb_comptes FROM t_compte_cpt;");
		return $query->row();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Regarde si un pseudo existe dans la base
	public function get_pseudo($pseudo){
		$query=$this->db->query("SELECT cpt_pseudo FROM t_compte_cpt
								WHERE cpt_pseudo = '".$pseudo."'
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Récupère toutes les informations d'une personne dont la chaîne $prenom est comprise dans soit le prénom de la personne soit sont pseudo
	public function get_1_person($prenom){
		$query=$this->db->query("SELECT *, IntToPseudo(cpt_id) as pseudo FROM t_profil_pfl
								JOIN t_compte_cpt USING(cpt_id)
								WHERE pfl_prenom LIKE '%".$prenom."%'
								OR cpt_pseudo LIKE '%".$prenom."%';
								");
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Récupère l'id, le prénom et le pseudo de tous les comptes administrateur/président et professeur
	public function get_all_prof_admin(){
		$query=$this->db->query("SELECT cpt_id, cpt_pseudo, pfl_prenom FROM t_compte_cpt
								JOIN t_profil_pfl USING(cpt_id)
								WHERE pfl_role = 'A'
								OR pfl_role = 'D'
								Or pfl_role = 'P';
								");
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Vérifie qu'un compte existe à partir de son pseudo
	public function compte_exist($pseudo){

		$query=$this->db->query("SELECT * FROM t_compte_cpt
								JOIN t_profil_pfl USING(cpt_id)
								WHERE cpt_pseudo = '".$pseudo."'
								AND pfl_etat = 'A';
								");

		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne la photo de profil d'un compte dont on connait le pseudo
	public function get_pp($pseudo){
		$query=$this->db->query("SELECT pfl_pp FROM t_profil_pfl
								WHERE cpt_id = PseudoToInt('".$pseudo."');
								");
		return $query->row();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           UPDATE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Modifie le pseudo de l'utilisateur connecté
	public function update_password($username, $mdp){

		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$password = hash('sha256', $salt.$mdp);

		$query=$this->db->query(" UPDATE t_compte_cpt SET cpt_mdp='" .$password. "' WHERE cpt_pseudo='" .$username. "';
								");

		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Met à jour le prénom, le nom et le mail d'un compte dont on connait le pseudo
	public function update_profil($username, $nv_pseudo, $prenom, $nom, $mail){
		$query=$this->db->query(" UPDATE t_profil_pfl
								JOIN t_compte_cpt USING(cpt_id)
								SET cpt_pseudo = '".$nv_pseudo."', pfl_prenom = '".$prenom."', pfl_nom = '".$nom."', pfl_mail = '".$mail."'
								WHERE t_profil_pfl.cpt_id = PseudoToInt('".$username."')
								AND t_compte_cpt.cpt_id = PseudoToInt('".$username."');
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Diminue le rôle d'un compte selon son rôle actuel à partir de son pseudo
	public function update_dim_role($username){
		$query=$this->db->query("CALL diminuer_role('".$username."');");
		return ($query);
	}
	//Augmente le rôle d'un compte selon son rôle actuel à partir de son pseudo
	public function update_aug_role($username){
		$query=$this->db->query("CALL augmenter_role('".$username."');");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Désactive/active un compte selon son état actuel à partir de son pseudo
	public function update_etat($username){
		$query=$this->db->query("CALL activer_desactiver('".$username."');");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Modifie le nom de la photo de profil
	public function update_pp($pp, $username){
		$query=$this->db->query("UPDATE t_profil_pfl SET pfl_pp = '".$pp."' 
								WHERE cpt_id = PseudoToInt('".$username."');
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           DELETE           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Supprime un profil dont on connait le pseudo
	//Grâce au trigger delete_compte, le compte associé sera automatiquement supprimé
	public function delete_compte($username){
		$query=$this->db->query("DELETE FROM t_profil_pfl WHERE cpt_id = PseudoToInt('".$username."');");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           INSERT           ///////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//créer un compte et son profil associé

	public function insert_compte($pseudo, $mdp){
		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$password = hash('sha256', $salt.$mdp);

		$query=$this->db->query(" INSERT INTO `t_compte_cpt` (`cpt_id`, `cpt_pseudo`, `cpt_mdp`)
								VALUES (NULL, '".$pseudo."', '".$password."');
								");
		return ($query);
	}
	public function insert_profil($pseudo, $prenom, $nom, $mail){
		$query=$this->db->query(" INSERT INTO `t_profil_pfl` (`pfl_id`, `pfl_prenom`, `pfl_nom`, `pfl_mail`, `pfl_role`, `pfl_etat`, `pfl_pp`, `cpt_id`)
								VALUES ( NULL, '".$prenom."', '".$nom."', '".$mail."', 'M', 'A', 'default.png', PseudoToInt('".$pseudo."') );
								");
		return ($query);
	}
}