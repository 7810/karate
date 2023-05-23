<!--
Nom du fichier: Professeur.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Professeur pour la gestion des pages de l'espace privé des professeurs
-->

<?php
class Professeur extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_comptes');
        $this->load->helper('url_helper');
    }
    
    // Affiche le profil du professeur connecté
    public function profil(){

        $username = $this->session->userdata('username');

        $data['info_profil'] = $this->db_comptes->get_all_info_profil($username);

        $this->load->view('templates/haut_prof');
        $this->load->view('professeur/profil_professeur', $data);
        $this->load->view('templates/bas_connectes');
    }

    // Affiche le formulaire de modification du profil de la personne connecté et le modifie en conséquence
    public function modifier_profil(){

        $username = $this->session->userdata('username');
        $role = $this->session->userdata('role');

        //On empêche les visiteurs d'avoir accès aux formulaires
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
            redirect(base_url()."index.php/compte/connecter");
        //On empêche les professeurs d'avoir accès aux formulaires via l'URL des admin
        }else if($role == 'A' || $role == 'D'){
            redirect(base_url()."index.php/administrateur/profil");
        //On empêche les membres d'avoir accès aux formulaires via l'URL des admin
        }else if($role == 'M'){
            redirect(base_url()."index.php/membre/profil");
        }else{

            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('pseudo', 'pseudo', 'required');

            $data['info_profil'] = $this->db_comptes->get_all_info_profil($username);

            if ($this->form_validation->run() == FALSE){

                $this->load->view('templates/haut_prof');
                $this->load->view('compte/modifier_profil', $data);
                $this->load->view('templates/bas_connectes');
                
            }else{

                $nv_pseudo = htmlspecialchars(addslashes($this->input->post('pseudo')));
                $prenom = htmlspecialchars(addslashes($this->input->post('prenom')));
                $nom = htmlspecialchars(addslashes($this->input->post('nom')));
                $mail = htmlspecialchars(addslashes($this->input->post('mail')));

                // Si l'adresse mail entrée par l'utilisateur n'est pas valide
                // Ou si les chaine de caractères dépasse les 45 caractères
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mail) > 45 || strlen($nv_pseudo) > 45 || strlen($prenom) > 45 || strlen($nom) > 45) {

                    //On vérifie que le compte de l'utilisateur existe toujours
                    //(il a pu être supprimée entre temps par un autre utilisateur)
                    if(!$this->db_comptes->compte_exist($username)){

                        echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                        echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                    }else{
                        ?><script>alert("Vos informations ne respectent pas le format requis")</script><?php
                        $this->load->view('templates/haut_prof');
                        $this->load->view('compte/modifier_profil', $data);
                        $this->load->view('templates/bas_connectes');
                    }

                }else{

                    //On vérifie que le compte de l'utilisateur existe toujours
                    //(il a pu être supprimée entre temps par un autre utilisateur)
                    if(!$this->db_comptes->compte_exist($username)){

                        echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                        echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                    }else{
                        // modification des informations personnelles
                        $this->db_comptes->update_profil($username, $nv_pseudo, $prenom, $nom, $mail);

                        //modification des informations de session
                        $session_data = array('username' => $nv_pseudo,'role' => $role );
                        $this->session->set_userdata($session_data);

                        $data['info_profil'] = $this->db_comptes->get_all_info_profil($nv_pseudo);

                        ?><script>alert("Vos informations personnelles ont bien été modifiées")</script><?php
                        $this->load->view('templates/haut_prof');
                        $this->load->view('compte/modifier_profil', $data);
                        $this->load->view('templates/bas_connectes');  
                    } 

                }

            }
        }
    }

    // Affiche le formulaire de modification du mot de passe de la personne connecté et le modifie en conséquence
    public function modifier_mdp(){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mdp', 'mdp', 'required');
        $this->form_validation->set_rules('nv_mdp', 'nv_mdp', 'required');
        $this->form_validation->set_rules('conf_nv_mdp', 'conf_nv_mdp', 'required');

        $username = $this->session->userdata('username');

        $data['info_profil'] = $this->db_comptes->get_all_info_profil($username);

        if ($this->form_validation->run() == FALSE){

            $this->load->view('templates/haut_prof');
            $this->load->view('compte/modifier_profil', $data);
            $this->load->view('templates/bas_connectes');
            
        }else{

            $old_mdp = htmlspecialchars(addslashes($this->input->post('mdp')));
            $mdp = htmlspecialchars(addslashes($this->input->post('nv_mdp')));
            $conf = htmlspecialchars(addslashes($this->input->post('conf_nv_mdp')));

            //Si le mot de passe actuel à correctement été renseigné
            if($this->db_comptes->bon_mdp($username, $old_mdp)){

                //si le mot de passe a été correctement confirmé (si les deux chaînes de caractères sont égales)
                if (strcmp($mdp, $conf) == 0){
        
                    // Si le mot de passe n'a pas au moins 1 chiffre, 1 minuscule, 1 majuscule et 8 caractères
                    // On prévient l'utilisateur que le mot de passe n'est pas au bon format puis on le redirige vers le formulaire
                    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $mdp)) {

                        //On vérifie que le compte de l'utilisateur existe toujours
                        //(il a pu être supprimée entre temps par un autre utilisateur)
                        if(!$this->db_comptes->compte_exist($username)){

                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                        }else{
                            ?><script>alert("Veuillez respecter le format du mot de passe\nMinimum 1 chiffre, 1 minuscule, 1 majuscule et 8 caractères")</script><?php
                            $this->load->view('templates/haut_prof');
                            $this->load->view('compte/modifier_profil', $data);
                            $this->load->view('templates/bas_connectes');
                        }

                    // Si le mot de passe dépasse les 40 caractères
                    // On prévient l'utilisateur que le mot de passe n'est pas au bon format puis on le redirige vers le formulaire
                    }else if (strlen($mdp) > 40){

                        //On vérifie que le compte de l'utilisateur existe toujours
                        //(il a pu être supprimée entre temps par un autre utilisateur)
                        if(!$this->db_comptes->compte_exist($username)){

                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                        }else{
                            ?><script>alert("Votre mot de passe est trop long, veuillez entrer un mot de passe plus court")</script><?php
                            $this->load->view('templates/haut_prof');
                            $this->load->view('compte/modifier_profil', $data);
                            $this->load->view('templates/bas_connectes');
                        }

                    }else{
                        //On vérifie que le compte de l'utilisateur existe toujours
                        //(il a pu être supprimée entre temps par un autre utilisateur)
                        if(!$this->db_comptes->compte_exist($username)){

                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                        }else{
                            //modification du mot de passe
                            $this->db_comptes->update_password($username, $mdp);

                            ?><script>alert("Votre mot de passe à bien été modifié")</script><?php
                            $this->load->view('templates/haut_prof');
                            $this->load->view('compte/modifier_profil', $data);
                            $this->load->view('templates/bas_connectes');
                        }

                    }

                }else{
                    //On vérifie que le compte de l'utilisateur existe toujours
                    //(il a pu être supprimée entre temps par un autre utilisateur)
                    if(!$this->db_comptes->compte_exist($username)){

                        echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                        echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                    }else{
                        ?><script>alert("Veuillez confirmer correctement votre mot de passe")</script><?php
                        $this->load->view('templates/haut_prof');
                        $this->load->view('compte/modifier_profil', $data);
                        $this->load->view('templates/bas_connectes');
                    }
        
                } 
                
            }else{
                //On vérifie que le compte de l'utilisateur existe toujours
                //(il a pu être supprimée entre temps par un autre utilisateur)
                if(!$this->db_comptes->compte_exist($username)){

                    echo "<script>alert('Votre compte a été supprimé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";

                }else{
                    ?><script>alert("Votre mot de passe actuel est incorrect")</script><?php
                    $this->load->view('templates/haut_prof');
                    $this->load->view('compte/modifier_profil', $data);
                    $this->load->view('templates/bas_connectes');
                }

            }
        }
    }

    // Affiche tous les profils de tous les comptes
    public function list_profils(){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('search', 'search', 'required');

        $data['profils'] = $this->db_comptes->get_all_comptes();
        $data['nb_comptes'] = $this->db_comptes->get_count_comptes();
        $data['prenom'] = null;

        if ($this->form_validation->run() == FALSE){
            $this->load->view('templates/haut_prof');
            $this->load->view('professeur/all_profils', $data);
            $this->load->view('templates/bas_connectes');
        }else{

            $prenom = htmlspecialchars(addslashes($this->input->post('search')));

            redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);

        }

        
    }

    // Affiche 0, 1 ou plusieurs profils d'une personne dont on connait le prénom
    public function profil_filtre($prenom){
        $data['person'] = $this->db_comptes->get_1_person($prenom);
        $data['prenom'] = $prenom;
        
        $this->load->view('templates/haut_prof');
        $this->load->view('professeur/profil_filtre', $data);
        $this->load->view('templates/bas_connectes');
    }

    // Augmente le rôle d'une personne et redirige vers la liste de tous les profils
    public function augmenter_role($pseudo){

        //role de l'utilisateur auquel on souhaite augmenter le role du compte
        $role_user = $this->db_comptes->get_role($pseudo);

        //id de l'utilisateur dont on veut diminuer le compte
        $id = $this->db_comptes->get_all_info_profil($pseudo);

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir augmenter le role d'un compte
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir augmenter le role d'un compte
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else if ($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){

            redirect(base_url()."index.php/administrateur/profil");

        }else{

            // Si le professeur essaie d'augmenter le role d'un compte qui n'est pas un membre ou son propre compte
            //on le redirige sans augmenter le role du compte
            if($role_user->pfl_role != 'M' || strtoupper($this->session->userdata('username')) == strtoupper($pseudo)){
                redirect(base_url()."index.php/professeur/list_profils");
            }else{
                 $this->db_comptes->update_aug_role($pseudo);

                redirect(base_url()."index.php/professeur/list_profils#" . $id->cpt_id);
            }
        } 
    }
    // Augmente le rôle d'une personne et redirige vers la liste des profils recherché par l'utilisateur connecté
    public function augmenter_role_filtre($pseudo, $prenom){

        //role de l'utilisateur auquel on souhaite augmenter le role du compte
        $role_user = $this->db_comptes->get_role($pseudo);

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir augmenter le role d'un compte
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir augmenter le role d'un compte
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else if ($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){

            redirect(base_url()."index.php/administrateur/profil");

        }else{

            // Si le professeur essaie d'augmenter le role d'un compte qui n'est pas un membre ou son propre compte
            //on le redirige sans augmenter le role du compte
            if($role_user->pfl_role != 'M' || strtoupper($this->session->userdata('username')) == strtoupper($pseudo)){
                redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);
            }else{
                $this->db_comptes->update_aug_role($pseudo);

                redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);
            }
        }
    }

    // Active ou désactive le compte d'une personne et redirige vers la liste de tous les profils
    public function activer_desactiver($pseudo){

        //role de l'utilisateur auquel on souhaite activer/désactiver le compte
        $role_user = $this->db_comptes->get_role($pseudo);

        //id de l'utilisateur dont on veut diminuer le compte
        $id = $this->db_comptes->get_all_info_profil($pseudo);

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir activer/désactiver un compte
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir activer/désactiver un compte
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else if ($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){

            redirect(base_url()."index.php/administrateur/profil");

        }else{

            // Si le professeur essaie d'activer/désactiver un compte qui n'est pas un membre ou son propre compte
            //on le redirige sans activer/désactiver le compte
            if($role_user->pfl_role != 'M' || strtoupper($this->session->userdata('username')) == strtoupper($pseudo)){

                redirect(base_url()."index.php/professeur/list_profils");

            }else{
                //Modifie l'état du compte selon son état actuel
                $this->db_comptes->update_etat($pseudo);

                //Redirige le professeur vers la liste des profils
                redirect(base_url()."index.php/professeur/list_profils#" . $id->cpt_id);
            }

        }
    }
    // Active ou désactive le compte d'une personne et redirige vers la liste des profils recherché par l'utilisateur connecté
    public function activer_desactiver_filtre($pseudo, $prenom){

        //role de l'utilisateur auquel on souhaite activer/désactiver le compte
        $role_user = $this->db_comptes->get_role($pseudo);

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir activer/désactiver un compte
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir activer/désactiver un compte
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else if ($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){

            redirect(base_url()."index.php/administrateur/profil");

        }else{
            // Si le professeur essaie d'activer/désactiver un compte qui n'est pas un membre ou son propre compte
            //on le redirige sans activer/désactiver le compte
            if($role_user->pfl_role != 'M' || strtoupper($this->session->userdata('username')) == strtoupper($pseudo)){

                redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);

            }else{
                //Modifie l'état du compte selon son état actuel
                $this->db_comptes->update_etat($pseudo);

                //Redirige le prof vers la liste des profils
                redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);
            }
        }
       
    }

    // Supprime le compte d'une personne et redirige vers la liste de tous les profils
    public function supprimer_compte($pseudo){

        //Role de l'utilisateur auquel on souhaite supprimer le compte
        $role_user = $this->db_comptes->get_role($pseudo);

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir supprimer un compte
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir supprimer un compte
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else if ($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){

            redirect(base_url()."index.php/administrateur/profil");

        }else{
            
            // Si le professeur essaie de supprimer un compte qui n'est pas un membre ou son propre compte
            //on le redirige sans supprimer le compte
            if($role_user->pfl_role != 'M' || strtoupper($this->session->userdata('username')) == strtoupper($pseudo)){

                redirect(base_url()."index.php/professeur/list_profils");

            }else{

                //Supprime le compte
                $this->db_comptes->delete_compte($pseudo);

                //Redirige le professeur vers la liste des profils
                redirect(base_url()."index.php/professeur/list_profils");

            }
        }
    }
    // Supprime le compte d'une personne et redirige vers la liste des profils recherché par l'utilisateur connecté
    public function supprimer_compte_filtre($pseudo, $prenom){

        //Role de l'utilisateur auquel on souhaite supprimer le compte
        $role_user = $this->db_comptes->get_role($pseudo);

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir supprimer un compte
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir supprimer un compte
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else if ($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){

            redirect(base_url()."index.php/administrateur/profil");

        }else{

            // Si le professeur essaie de supprimer un compte qui n'est pas un membre ou son propre compte
            //on le redirige sans supprimer le compte
            if($role_user->pfl_role != 'M' || strtoupper($this->session->userdata('username')) == strtoupper($pseudo)){
                redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);
            }else{
                //Supprime le compte
                $this->db_comptes->delete_compte($pseudo);

                //Redirige le professeur vers la liste des profils filtrés
                redirect(base_url()."index.php/professeur/profil_filtre/". $prenom);
            }
        }
    }

}