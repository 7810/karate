<!--
Nom du fichier: Db_model.php
Auteur: Julie STEPHANT
Date de création: 12/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Fichier du MODEL pour l'architecture MVC
1 seul fichier pour toutes les requêtes
-->

<!--
return $query->row(); 			-> pour retourner une seule ligne
return $query->result_array(); 	-> pour retourner plusieurs lignes
return ($query);				-> pour les fonction d'update
-->

<?php
class Db_model extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           COMPTES           //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//pour saler et hacher le mot de passe à l'installation de la bdd
	/*public function saler_hacher($id, $password){

		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$mdp = hash('sha256', $salt.$password);

		$query=$this->db->query("UPDATE t_compte_cpt SET cpt_mdp = '".$mdp."' WHERE cpt_id = '".$id."'");
		return ($query);
	}*/

	/* copier coller dans controller accueil
	$this->db_model->saler_hacher(1, "wado_ryu_queven");
    $this->db_model->saler_hacher(2, "Pascal#56");
    $this->db_model->saler_hacher(3, "Martin&56");
    $this->db_model->saler_hacher(4, "Ana#29");
    $this->db_model->saler_hacher(5, "Ila56nn");
    $this->db_model->saler_hacher(6, "Ju56ju");
    $this->db_model->saler_hacher(7, "Clem809");
    $this->db_model->saler_hacher(8, "Maxou78");*/
	

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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Modifie le pseudo de l'utilisateur connecté
	public function update_password($username, $mdp){

		$salt = "629Vj2e9GXg4GsW6y5kDVCgmH5X9r78ZzU8vrNxw6iU9Kic8Q8";
		$password = hash('sha256', $salt.$mdp);

		$query=$this->db->query(" UPDATE t_compte_cpt SET cpt_mdp='" .$password. "' WHERE cpt_pseudo='" .$username. "';
								");

		return ($query);
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

	//Récupère toutes les informations de tous les profils
	public function get_all_comptes(){
		$query=$this->db->query("SELECT *, IntToPseudo(cpt_id) as pseudo FROM t_profil_pfl;");
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function get_count_comptes(){
		$query=$this->db->query("SELECT COUNT(*) as nb_comptes FROM t_compte_cpt;");
		return $query->row();
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

	//Supprime un profil dont on connait le pseudo
	//Grâce au trigger delete_compte, le compte associé sera automatiquement supprimé
	public function delete_compte($username){
		$query=$this->db->query("DELETE FROM t_profil_pfl WHERE cpt_id = PseudoToInt('".$username."');");
		return ($query);
	}

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
		$query=$this->db->query(" INSERT INTO `t_profil_pfl` (`pfl_id`, `pfl_prenom`, `pfl_nom`, `pfl_mail`, `pfl_role`, `pfl_etat`, `cpt_id`)
								VALUES ( NULL, '".$prenom."', '".$nom."', '".$mail."', 'M', 'A', PseudoToInt('".$pseudo."') );
								");
		return ($query);
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

	//Récupère toutes les informations de tous les profils
	public function get_1_person($prenom){
		$query=$this->db->query("SELECT *, IntToPseudo(cpt_id) as pseudo FROM t_profil_pfl
								WHERE pfl_prenom LIKE '%".$prenom."%';
								;");
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           PUBLICATIONS           /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// retourne les infos de toutes les publications de la date de publication la plus récente à la plus ancienne
	//DATE_FORMAT permet de formater une date (et/ou une heure si DATETIME) selon le format précisé
	public function get_all_publi(){
		$query=$this->db->query("SELECT pbl_id, pbl_img, pbl_description, DATE_FORMAT(pbl_date_publi, '%d/%m/%Y') as date_publi,
								DATE_FORMAT(pbl_date_publi, '%H:%i') as heure_publi, IntToPseudo(cpt_id) as pseudo 
								FROM t_publication_pbl
								ORDER BY pbl_date_publi DESC;
								");
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Retourne une seule publication dont on connait l'id
	public function get_one_publi($id){
		$query=$this->db->query(" SELECT pbl_id, pbl_img, pbl_description, DATE_FORMAT(pbl_date_publi, '%d/%m/%Y') as date_publi,
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

	// Supprime une publication dont on connait l'id
	// (Tous les likes et commentaires seront supprimés grâce à un trigger)
	public function delete_publi($id){
		$query=$this->db->query("DELETE FROM t_publication_pbl WHERE pbl_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// Modifie la description d'une publication dont on connait l'id
	public function update_publication($description, $id){
		$query=$this->db->query(" UPDATE t_publication_pbl
								SET pbl_description = '".$description."' 
								WHERE pbl_id = ".$id.";
								");
		return ($query);
	}

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
	/////////////////////////////////////////           AIMER/COMMENTER           //////////////////////////////////////
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

	// Ajoute le like de l'utilisateur connecté à une publication dont ont connait l'id
	public function insert_like($pseudo, $id_publi){
		$query=$this->db->query(" INSERT INTO `t_aimer_aim` (`cpt_id`, `pbl_id`)
								VALUES (PseudoToInt('".$pseudo."'), ".$id_publi.");
								");
		return ($query);
	}

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

	// Insère un nouveau commentaire de l'utilisateur connecté pour une publication dont l'id est connu
	public function insert_commentaire($pseudo, $id_publi, $contenu){
		$query=$this->db->query(" INSERT INTO `t_commenter_com` (`com_id`, `cpt_id`, `pbl_id`, `com_contenu`, `com_date_publi`)
								VALUES (NULL, PseudoToInt('".$pseudo."'), ".$id_publi.", '".$contenu."', NOW());
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

	// Modifie les informations d'un commentaire dont on connait l'id
	public function update_commentaire($contenu, $id){
		$query=$this->db->query(" UPDATE t_commenter_com
								SET com_contenu = '".$contenu."' 
								WHERE com_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
	/////////////////////////////////////////           CLUB           /////////////////////////////////////////////////
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

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////           ACTIVITES           ////////////////////////////////////////////
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

	//Active ou annule une activité dont on connait l'id selon son état actuel
	public function update_etat_activite($id){
		$query=$this->db->query("CALL activer_annuler('".$id."');");
		return ($query);
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

	//Modifie les informations d'une activité dont on connait l'id
	public function update_activite($id, $intitule, $description, $lieu, $date_debut, $date_fin){
		$query=$this->db->query("UPDATE t_activite_act
								SET act_intitule = '".$intitule."',
								act_description = '".$description."',
								act_lieu = '".$lieu."',
								act_date_debut = '".$date_debut."',
								act_date_fin = '".$date_fin."'
								WHERE act_id = ".$id.";
								");
		return ($query);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Insert une nouvelle activité
	public function insert_activite($intitule, $description, $lieu, $date_debut, $date_fin, $pseudo){
		$query=$this->db->query("INSERT INTO `t_activite_act` (`act_id`, `act_intitule`, `act_description`, `act_date_debut`, `act_date_fin`, `act_lieu`, `act_etat`, `cpt_id`)
								VALUES (NULL, '".$intitule."', '".$description."', '".$date_debut."', '".$date_fin."', '".$lieu."', 'A', PseudoToInt('".$pseudo."'));
								");
		return ($query);
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

}
?>