<!--
Nom du fichier: Compte.php
Auteur: Julie STEPHANT
Date de création: 12/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Compte pour la gestion de la page de connexion et d'inscription
-->

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Compte extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_comptes');
        $this->load->helper('url_helper');
    }

    // Permet de se connecter à son espace privé
    public function connecter(){

        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('pseudo', 'pseudo', 'required');
        $this->form_validation->set_rules('mdp', 'mdp', 'required');

        $data['president_mail'] = $this->db_comptes->get_mail_president();

        if ($this->form_validation->run() == FALSE){

            $this->load->view('templates/haut_accueil');
            $this->load->view('compte/connexion', $data);
            $this->load->view('templates/bas_accueil');
            
        }else{

            $username = htmlspecialchars(addslashes($this->input->post('pseudo')));
            $password = htmlspecialchars(addslashes($this->input->post('mdp')));
            
            //si le compte auquel l'utilisateur essaie de se connecter existe dans la base
            if($this->db_comptes->connect($username,$password)){ 

                //On récupère le rôle de l'utilisateur
                $role = $this->db_comptes->get_role($username);

                //conservation du pseudo et du role de l'utilisateur connecté
                $session_data = array('username' => $username,'role' => $role->pfl_role );
                $this->session->set_userdata($session_data);
                
                //si c'est un administrateur ou le président
                if($role->pfl_role == 'A' || $role->pfl_role == 'D'){     

                    redirect(base_url()."index.php/administrateur/profil/");

                //si c'est un professeur    
                }else if($role->pfl_role == 'P'){
                    
                    redirect(base_url()."index.php/professeur/profil/");

                //si c'est un membre
                }else{

                    redirect(base_url()."index.php/membre/profil/");

                }

                $this->load->view('templates/bas_accueil');

            }else{
                echo '<script>alert("Identifiants incorrects ou votre compte est désactivé")</script>';
                $this->load->view('templates/haut_accueil');
                $this->load->view('compte/connexion', $data);
                $this->load->view('templates/bas_accueil'); 
            }

        }
    }

    // Permet de se déconnecter de son espace privé
    public function deconnexion(){
        $this->session->unset_userdata($session_data);
        $this->session->sess_destroy();
        redirect(base_url()."index.php/compte/connecter");
    }

    // Permet d'inscrire un nouveau membre au club
    public function inscription(){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pseudo', 'pseudo', 'required');
        $this->form_validation->set_rules('prenom', 'prenom', 'required');
        $this->form_validation->set_rules('nom', 'nom', 'required');
        $this->form_validation->set_rules('mail', 'mail', 'required');
        $this->form_validation->set_rules('mdp', 'mdp', 'required');
        $this->form_validation->set_rules('conf_mdp', 'conf_mdp', 'required');

        $role = $this->session->userdata('role');
        $username = $this->session->userdata('username');

        if ($this->form_validation->run() == FALSE){

            $this->load->view('templates/haut_admin');
            $this->load->view('compte/inscription');
            $this->load->view('templates/bas_connectes');
            
        }else{

            $pseudo = htmlspecialchars(addslashes($this->input->post('pseudo')));
            $prenom = htmlspecialchars(addslashes($this->input->post('prenom')));
            $nom = htmlspecialchars(addslashes($this->input->post('nom')));
            $mail = htmlspecialchars(addslashes($this->input->post('mail')));
            $mdp = htmlspecialchars(addslashes($this->input->post('mdp')));
            $conf_mdp = htmlspecialchars(addslashes($this->input->post('conf_mdp')));

            //On vérifie que le pseudo n'existe pas déjà dans la base
            $pseudo_base = $this->db_comptes->get_pseudo($pseudo);

            //S'il existe déjà on affiche un message et on réaffiche le formulaire
            if($pseudo_base != NULL){

                ?><script>alert("Ce pseudo existe déjà")</script><?php
                $this->load->view('templates/haut_admin');
                $this->load->view('compte/inscription');
                $this->load->view('templates/bas_connectes');

            }else{

                //si le mot de passe a été correctement confirmé (si les deux chaînes de caractères sont égales)
                if (strcmp($mdp, $conf_mdp) == 0){

                    // Si le mot de passe n'a pas au moins 1 chiffre, 1 minuscule, 1 majuscule et 8 caractères
                    // On prévient l'utilisateur que le mot de passe n'est pas au bon format puis on le redirige vers le formulaire
                    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $mdp)) {
                        //On vérifie que le compte de l'utilisateur existe toujours
                        if(!$this->db_comptes->compte_exist($username)){
                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                        }else{
                            ?><script>alert("Veuillez respecter le format du mot de passe\nMinimum 1 chiffre, 1 minuscule, 1 majuscule et 8 caractères")</script><?php
                            $this->load->view('templates/haut_admin');
                            $this->load->view('compte/inscription');
                            $this->load->view('templates/bas_connectes');
                        }

                    // Si le mot de passe dépasse les 40 caractères
                    // On prévient l'utilisateur que le mot de passe n'est pas au bon format puis on le redirige vers le formulaire
                    }else if (strlen($mdp) > 40){
                        //On vérifie que le compte de l'utilisateur existe toujours
                        if(!$this->db_comptes->compte_exist($username)){
                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                           // echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                        }else{
                            ?><script>alert("Le mot de passe est trop long, veuillez entrer un mot de passe plus court")</script><?php
                            $this->load->view('templates/haut_admin');
                            $this->load->view('compte/inscription');
                            $this->load->view('templates/bas_connectes');
                        }

                    // Si l'adresse mail entrée par l'utilisateur n'est pas valide
                    // Ou si les chaines de caractère dépassent les 45 caractères
                    }else if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mail) > 45 || strlen($pseudo) > 45 || strlen($prenom) > 45 || strlen($nom) > 45) {

                        //On vérifie que le compte de l'utilisateur existe toujours
                        if(!$this->db_comptes->compte_exist($username)){
                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                        }else{
                            ?><script>alert("Les informations entrées ne respectent pas le format requis")</script><?php
                            $this->load->view('templates/haut_admin');
                            $this->load->view('compte/inscription');
                            $this->load->view('templates/bas_connectes');
                        }

                    }else{

                        //On vérifie que le compte de l'utilisateur existe toujours
                        if(!$this->db_comptes->compte_exist($username)){
                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                        }else{
                            //On insert le compte et le profil associé dans la base
                            $this->db_comptes->insert_compte($pseudo, $mdp);
                            $this->db_comptes->insert_profil($pseudo, $prenom, $nom, $mail);

                            redirect(base_url()."index.php/administrateur/list_profils");
                        }

                    }

                }else{

                    //On vérifie que le compte de l'utilisateur existe toujours
                    if(!$this->db_comptes->compte_exist($username)){
                        echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                        echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                    }else{
                        ?><script>alert("Veuillez confirmer correctement le mot de passe")</script><?php
                        $this->load->view('templates/haut_admin');
                        $this->load->view('compte/inscription');
                        $this->load->view('templates/bas_connectes');
                    }

                }
            }
        }
    }

    //Permet de modifier la photo de profil d'un utilisateur
    public function modifier_pp(){

        $this->load->helper('form');

        // Définir le fuseau horaire sur "Europe/Paris"
        // Permettra de mettre un suffixe avec la date et l'heure à laquelle chaque fichier sera téléversé pour s'assurer qu'il soit unique
        date_default_timezone_set('Europe/Paris');

        //Role de la personne connectée
        $role = $this->session->userdata('role');
        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        $ancienne_pp = $this->db_comptes->get_pp($username);

        //Si un fichier a été sélectionné
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // traitement des données envoyées par le formulaire
            
            // Chemin absolu du répertoire où le fichier sera téléversé
            // FCPATH spécifie le chemin absolu depuis la racine du projet
            $uploaddir = FCPATH . "style/img/pp/";

            //Nom du fichier qui remplace les espaces du nom par des underscore (sinon problème d'affichage)
            $nom_fichier = str_replace(' ', '_', $_FILES['pp_file']['name']);
            // Concaténation du nom de fichier avec la date et l'heure actuelle pour que le nom soit unique
            $filename =  date("YmdHis") . "_" . $nom_fichier;

            // Chemin complet du fichier que l'utilisateur a téléversé + nom et extention du fichier
            $uploadfile = $uploaddir . basename(str_replace(" ", "_", $filename));

            //La liste des types de fichiers étant autorisés
            $types_autorises = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
            //Le type du fichier que l'utilisateur veut téléverser
            $type_fichier = $_FILES['pp_file']['type'];

            //Si l'extension du fichier que l'utilisateur veut téléverser est dans les types autorisés
            if (in_array($type_fichier, $types_autorises)) {
                
                //Vérification de la validité du fichier
                if (move_uploaded_file($_FILES['pp_file']['tmp_name'], $uploadfile)) {

                    //On vérifie que le compte de l'utilisateur existe toujours
                    if(!$this->db_comptes->compte_exist($username)){

                        echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                        echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                    
                    }else{

                        //Supprime l'ancienne photo de profil du dossierdes photos de profil
                        //SAUF si c'est la photo de profil par défaut
                        if($ancienne_pp->pfl_pp != 'default.png'){
                            $pp_path = FCPATH . "style/img/pp/" . $ancienne_pp->pfl_pp;
                            if (file_exists($pp_path)) {
                                unlink($pp_path);
                            }
                        }

                        //Modifie la photo de profil de l'utilisateur puis le redirige vers son profil
                        $this->db_comptes->update_pp($filename, $username);
                        if($role == 'A' || $role == 'D'){
                            redirect(base_url()."index.php/administrateur/profil");
                        }else if($role == 'P'){
                            redirect(base_url()."index.php/professeur/profil");
                        }else{
                            redirect(base_url()."index.php/membre/profil");
                        }

                    }

                } else {
                    //Problème lors du téléversement
                    ?><script>alert("Le fichier n’a pas été téléchargé. Il y a eu un problème")</script><?php
                    if($role == 'A' || $role == 'D'){
                        $this->load->view('templates/haut_admin');
                    }else if($role == 'P'){
                        $this->load->view('templates/haut_prof');
                    }else{
                        $this->load->view('templates/haut_membre');
                    }
                    $this->load->view('compte/modifier_pp');
                    $this->load->view('templates/bas_connectes');
                }

            } else {

                // Le type de fichier n'est pas autorisé
                ?><script>alert("Ce type de fichier n'est pas autorisé")</script><?php
                if($role == 'A' || $role == 'D'){
                    $this->load->view('templates/haut_admin');
                }else if($role == 'P'){
                    $this->load->view('templates/haut_prof');
                }else{
                    $this->load->view('templates/haut_membre');
                }
                $this->load->view('compte/modifier_pp');
                $this->load->view('templates/bas_connectes');

            }

        }else{

            if ($role == 'A' || $role == 'D') {
                $this->load->view('templates/haut_admin');
            } else if ($role == 'P') {
                $this->load->view('templates/haut_prof');
            } else {
                $this->load->view('templates/haut_membre');
            }
            $this->load->view('compte/modifier_pp');
            $this->load->view('templates/bas_connectes');

        }
    }


}
?>