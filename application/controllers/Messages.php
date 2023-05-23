<!--
Nom du fichier: Messages.php
Auteur: Julie STEPHANT
Date de création: 09/05/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Messages pour la gestion du chat de l'application
-->

<?php
class Messages extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_comptes');
        $this->load->model('db_messages');
        $this->load->helper('url_helper');
    }

    // Affiche la page des conversations et le message "Aucune conversation selectionée"
    public function afficher(){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('search', 'search', 'required');

        //pseudo de l'utilisateur connecté
        $username =  $this->session->userdata('username');
        //role de l'utilisateur connecté
        $role =  $this->session->userdata('role');

        $data['comptes'] = $this->db_comptes->get_all_comptes();
        $data['date_last_message'] = array();
        $data['messages_non_lus'] = array();

        foreach($data['comptes'] as $compte){
            //id du compte
            $id = $compte['cpt_id'];
            //date du dernier message et nb de messages non lus
            $date = $this->db_messages->get_last_date($username, $compte['pseudo']);
            $nb_messages_non_lus = $this->db_messages->get_nb_non_lus($username, $compte['cpt_id']);
            //stocke dans les tableaux
            $data['date_last_message'][$id] = $date;
            $data['messages_non_lus'][$id] = $nb_messages_non_lus;
        }

        if ($this->form_validation->run() == FALSE){

            if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
                redirect(base_url()."index.php/compte/connecter");
            }else if($role == 'A' || $role == 'D'){
                $this->load->view('templates/haut_admin');
            }else if($role == 'P'){
                $this->load->view('templates/haut_prof');
            }else if($role == 'M'){
                $this->load->view('templates/haut_membre');
            }
            $this->load->view('messages/messages', $data);
            $this->load->view('messages/aucun_chat');
            $this->load->view('templates/bas_accueil');

        }else{

            $personne = htmlspecialchars(addslashes($this->input->post('search')));

            redirect(base_url()."index.php/messages/afficher_recherche/". $personne);

        }

    }

    //Affiche les comptes recherchés par l'utilisateur connecté et le message "Aucune conversation sélectionnée"
    public function afficher_recherche($personne){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['personne'] = $this->db_comptes->get_1_person($personne);
        $data['date_last_message'] = array();
        $data['messages_non_lus'] = array();

        //pseudo de l'utilisateur connecté
        $username =  $this->session->userdata('username');
        //role de l'utilisateur connecté
        $role =  $this->session->userdata('role');

        foreach($data['personne'] as $p){
            //id du compte
            $id = $p['cpt_id'];
            //date du dernier message et nb de messages non lus
            $date = $this->db_messages->get_last_date($username, $p['pseudo']);
            $nb_messages_non_lus = $this->db_messages->get_nb_non_lus($username, $p['cpt_id']);
            //stocke dans le tableau
            $data['date_last_message'][$id] = $date;
            $data['messages_non_lus'][$id] = $nb_messages_non_lus;
        }

        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
            redirect(base_url()."index.php/compte/connecter");
        }else if($role == 'A' || $role == 'D'){
            $this->load->view('templates/haut_admin');
        }else if($role == 'P'){
            $this->load->view('templates/haut_prof');
        }else if($role == 'M'){
            $this->load->view('templates/haut_membre');
        }
        $this->load->view('messages/messages_filtre', $data);
        $this->load->view('messages/aucun_chat');
        $this->load->view('templates/bas_accueil');

    }

    // Affiche la page des conversations et d'une conversation entre l'utilisateur connecté et le destinataire sélectionné
    public function afficher_conversation($id_destinataire){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('message_chat', 'message_chat', 'required');

        //pseudo de l'utilisateur connecté
        $username =  $this->session->userdata('username');
        //role de l'utilisateur connecté
        $role =  $this->session->userdata('role');

        $data['comptes'] = $this->db_comptes->get_all_comptes();
        $data['date_last_message'] = array();
        $data['messages_non_lus'] = array();

        $data['pseudo_destinataire'] = $this->db_comptes->get_pseudo_by_id($id_destinataire);
        $data['messages'] = $this->db_messages->get_messages($username, $id_destinataire);
        $data['id_destinataire'] = $id_destinataire;

        //L'id de la personne connectée
        $id_connecte = $this->db_comptes->get_id_by_pseudo($username);

        foreach($data['comptes'] as $compte){
            //id du compte
            $id = $compte['cpt_id'];
            //date du dernier message et nb de messages non lus
            $date = $this->db_messages->get_last_date($username, $compte['pseudo']);
            $nb_messages_non_lus = $this->db_messages->get_nb_non_lus($username, $compte['cpt_id']);
            //stocke dans le tableau
            $data['date_last_message'][$id] = $date;
            $data['messages_non_lus'][$id] = $nb_messages_non_lus;
        }

        //On met à jour les messages qui sont maintenant lus
        $this->db_messages->update_est_lu($username, $id_destinataire);

        if ($this->form_validation->run() == FALSE){

            //Si l'utilisateur essaie d'afficher une conversation avec lui-même
            if($id_destinataire == $id_connecte->cpt_id){
                redirect(base_url()."index.php/messages/afficher");
            //Si l'utilisateur est un visiteur
            }else if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
                redirect(base_url()."index.php/compte/connecter");
            //Chargement du menu selon le rôle de l'utilisateur connecté
            }else if($role == 'A' || $role == 'D'){
                $this->load->view('templates/haut_admin');
            }else if($role == 'P'){
                $this->load->view('templates/haut_prof');
            }else if($role == 'M'){
                $this->load->view('templates/haut_membre');
            }
            $this->load->view('messages/messages', $data);
            $this->load->view('messages/chat', $data);
            $this->load->view('templates/bas_accueil');

        }else{

            $message = htmlspecialchars(addslashes($this->input->post('message_chat')));

            if(!$this->db_comptes->compte_exist($username)){
                echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
            }else{
                //On insert le message dans la base
                $this->db_messages->insert_message($username, $id_destinataire, $message);

                //On redirige l'utilisateur
                redirect(base_url()."index.php/messages/afficher_conversation/" . $id_destinataire . "#haut");
            }

        }

    }

    //Permet à un utilisateur de supprimer son message
    public function supprimer($id, $id_destinataire){

        //Role de l'utilisateur connecté
        $role = $this->session->userdata('role');
        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir supprimer le message d'un utilisateur
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur n'est pas l'expéditeur du message, l'empêche de pouvoir le supprimer
        }else if(!$this->db_messages->is_user_message_expediteur($username, $id)){

            redirect(base_url()."index.php/messages/afficher");

        }else{

            //On supprime le message
            $this->db_messages->delete_message($id);

            //On redirige l'utilisateur
            redirect(base_url()."index.php/messages/afficher_conversation/" . $id_destinataire ."#haut");

        }

    }

}
?>