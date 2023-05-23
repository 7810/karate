<!--
Nom du fichier: Calendrier.php
Auteur: Julie STEPHANT
Date de création: 17/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Calendrier pour la gestion des activités
-->

<?php
class Calendrier extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_activites');
        $this->load->model('db_comptes');
        $this->load->helper('url_helper');
    }

    // Affiche le calendrier du club côté visiteur
    public function afficher(){
        $data['activites'] = $this->db_activites->get_all_activites();
        $data['nb_status_activites'] = $this->db_activites->get_nb_status_act();

        $this->load->view('templates/haut_accueil');
        $this->load->view('calendrier/page_calendrier', $data);
        $this->load->view('templates/bas_accueil');
    }    

    // Affiche le calendrier du club du côté des personnes connectées
    public function afficher_connectes(){

        //role de l'utilisateur connecté
        $role = $this->session->userdata('role');

        $data['activites'] = $this->db_activites->get_all_activites();

        // mettre les nb_sattus_activites dans les if sinon pose problème
        // si le résultats d'une requêtes n'est pas récupérée avant de lancer une autre requête, une erreur se produit

        if($role == 'D' || $role == 'A'){

            $data['nb_status_activites'] = $this->db_activites->get_nb_status_act_admin_prof();

            $this->load->view('templates/haut_admin');
            $this->load->view('calendrier/page_calendrier_admin', $data);   

        }else if($role == 'P'){

            $data['nb_status_activites'] = $this->db_activites->get_nb_status_act_admin_prof();

            $this->load->view('templates/haut_prof');
            $this->load->view('calendrier/page_calendrier_prof', $data);     
            
        }else{
            $data['nb_status_activites'] = $this->db_activites->get_nb_status_act();

            $this->load->view('templates/haut_membre');
            $this->load->view('calendrier/page_calendrier', $data);
        }
        $this->load->view('templates/bas_connectes');

    } 

    // Active ou annule une activité selon son état actuel
    public function annuler_active($id){

        //Role de l'utilisateur connecté
        $role = $this->session->userdata('role');
        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir modifier l'etat d'une activite
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        // Si l'utilisateur connecté est un membre, il ne peut pas annuler/activer une activité
        }else if ($role == 'M'){

            redirect(base_url()."index.php/membre/profil");

        // Si l'utilisateur connecté est un professeur et qu'il n'est pas auteur de l'activité, il ne peut pas l'annuler/activer
        }else if($role == 'P' && !$this->db_activites->is_user_activite_author($username, $id)){

            redirect(base_url()."index.php/calendrier/afficher_connectes");

        }else{
            //Modifie l'état de la publication
            $this->db_activites->update_etat_activite($id);

            //Et redirige l'utilisateur selon son rôle
            redirect(base_url()."index.php/calendrier/afficher_connectes#".$id);
        }

    }

    // Affiche le formulaire pour modifier une activité dont on connait l'id
    public function modifier_activite($id){

        $data['activite'] = $this->db_activites->get_activite($id);
        $data['prof_admin'] = $this->db_comptes->get_all_prof_admin();

        // Définir le fuseau horaire sur "Europe/Paris"
        date_default_timezone_set('Europe/Paris');
        //date et heure actuelle
        $now = date('Y-m-d H:i:s');

        //role de l'utilisateur connecté
        $role = $this->session->userdata('role');
        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir avoir accès au formulaire de modification d'une activite
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        // Si l'utilisateur connecté est un membre, on l'empêche de pouvoir avoir accès au formulaire de modification d'une activite
        }else if ($role == 'M'){

            redirect(base_url()."index.php/calendrier/afficher_connectes");

        // Si l'utilisateur connecté est un professeur et qu'il n'est pas auteur de l'activité, on l'empêche de pouvoir avoir accès au formulaire de modification d'une activite
        }else if($role == 'P' && !$this->db_activites->is_user_activite_author($username, $id) ){

            redirect(base_url()."index.php/calendrier/afficher_connectes");

        // Un utilisateur ne doit pas pouvoir accéder au formulaire de modification pour une activité en cours ou une activité passée
        }else if($data['activite']->act_date_debut < $now || $data['activite']->act_date_fin < $now){

            redirect(base_url()."index.php/calendrier/afficher_connectes");

        }else{
            
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('description', 'description', 'required');
            $this->form_validation->set_rules('intitule', 'intitule', 'required');
            $this->form_validation->set_rules('lieu', 'lieu', 'required');
            $this->form_validation->set_rules('date_debut', 'date_debut', 'required');
            $this->form_validation->set_rules('date_fin', 'date_fin', 'required');

            if ($this->form_validation->run() == FALSE){
                
                if($role == 'A' || $role == 'D'){
                    $this->load->view('templates/haut_admin');
                }else{
                    $this->load->view('templates/haut_prof');
                }
                $this->load->view('calendrier/modifier_activite', $data);
                $this->load->view('templates/bas_connectes');
                
            }else{

                $description = htmlspecialchars(addslashes($this->input->post('description')));
                $intitule = htmlspecialchars(addslashes($this->input->post('intitule')));
                $lieu = htmlspecialchars(addslashes($this->input->post('lieu')));
                $date_debut = htmlspecialchars(addslashes($this->input->post('date_debut')));
                $date_fin = htmlspecialchars(addslashes($this->input->post('date_fin')));
                //Si l'utilisateur est un administrateur ou le président, il peut modifier l'auteur de l'activité
                if($role == 'A' || $role == 'D'){
                    $auteur = $this->input->post('auteur');
                }else{
                    $auteur = $username;
                }

                //On vérifie que le compte de l'utilisateur connecté existe toujours
                if(!$this->db_comptes->compte_exist($username)){
                    echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                }else{
                    // Modifie l'activité
                    $this->db_activites->update_activite($id, $intitule, $description, $lieu, $date_debut, $date_fin, $auteur);

                    //Puis redirige l'utilisateur vers la page du calendrier
                    redirect(base_url()."index.php/calendrier/afficher_connectes#".$id);
                }

            }
        }
    }

    // Affiche le formulaire pour créer une nouvelle activité
    public function creer_activite(){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('description', 'description', 'required');
        $this->form_validation->set_rules('intitule', 'intitule', 'required');
        $this->form_validation->set_rules('lieu', 'lieu', 'required');
        $this->form_validation->set_rules('date_debut', 'date_debut', 'required');
        $this->form_validation->set_rules('date_fin', 'date_fin', 'required');

        // Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');
        //role de l'utilisateur connecté
        $role = $this->session->userdata('role');

        $data['prof_admin'] = $this->db_comptes->get_all_prof_admin();

        if ($this->form_validation->run() == FALSE){
            
            if($role == 'A' || $role == 'D'){
                $this->load->view('templates/haut_admin');
            }else{
                $this->load->view('templates/haut_prof');
            }
            $this->load->view('calendrier/creer_nv_act', $data);
            $this->load->view('templates/bas_connectes');
            
        }else{

            $description = htmlspecialchars(addslashes($this->input->post('description')));
            $intitule = htmlspecialchars(addslashes($this->input->post('intitule')));
            $lieu = htmlspecialchars(addslashes($this->input->post('lieu')));
            $date_debut = htmlspecialchars(addslashes($this->input->post('date_debut')));
            $date_fin = htmlspecialchars(addslashes($this->input->post('date_fin')));
            //Si l'utilisateur est un administrateur ou le président, il peut choisir l'auteur de l'activité
            if($role == 'A' || $role == 'D'){
                $auteur = $this->input->post('auteur');
            //Sinon, c'est le professeur lui même qui en est l'auteur
            }else{
                $auteur = $username;
            }

            //On vérifie que le compte de l'utilisateur connecté existe toujours
            if(!$this->db_comptes->compte_exist($username)){
                echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
            }else{
                // Insert l'actualité
                $this->db_activites->insert_activite($intitule, $description, $lieu, $date_debut, $date_fin, $auteur);

                //Puis redirige l'utilisateur vers la page du calendrier
                redirect(base_url()."index.php/calendrier/afficher_connectes/");
            }

        }

    }

}