<!--
Nom du fichier: Club.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Club pour la gestion du formulaire de modification des informations du club
-->

<?php
class Club extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_club');
        $this->load->model('db_comptes');
        $this->load->helper('url_helper');
    }

    // Affiche le formulaire de modification des informations du club
    public function modifier(){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nom', 'nom', 'required');
        $this->form_validation->set_rules('mail', 'mail', 'required');
        $this->form_validation->set_rules('tel', 'tel', 'required');
        $this->form_validation->set_rules('nom_rue', 'nom_rue', 'required');
        $this->form_validation->set_rules('code_postal', 'code_postal', 'required');
        $this->form_validation->set_rules('ville', 'ville', 'required');
        $this->form_validation->set_rules('pays', 'pays', 'required');
        $this->form_validation->set_rules('categorie', 'categorie', 'required');
        $this->form_validation->set_rules('a_propos', 'a_propos', 'required');

        $data['infos'] = $this->db_club->get_info_club();

        $username = $this->session->userdata('username');

        if ($this->form_validation->run() == FALSE){
            
            $this->load->view('templates/haut_admin');
            $this->load->view('club/modifier_club', $data);
            $this->load->view('templates/bas_connectes');
            
        }else{

            $nom = htmlspecialchars(addslashes($this->input->post('nom')));
            $mail = htmlspecialchars(addslashes($this->input->post('mail')));
            $tel = htmlspecialchars(addslashes($this->input->post('tel')));
            $nom_rue = htmlspecialchars(addslashes($this->input->post('nom_rue')));
            $code_postal = htmlspecialchars(addslashes($this->input->post('code_postal')));
            $ville = htmlspecialchars(addslashes($this->input->post('ville')));
            $pays = htmlspecialchars(addslashes($this->input->post('pays')));
            $categorie = htmlspecialchars(addslashes($this->input->post('categorie')));
            $a_propos = htmlspecialchars(addslashes($this->input->post('a_propos')));

            //Si les infos entrées ne respectent pas le format requis
            // (filter_var(var, FILTER_VALIDATE_EMAIL) vérifie qu'un email est valide et ctype_digit(var) vérifie qu'une chaine ne comporte que des chiffres)
            if (strlen($nom) > 45 || strlen($mail) > 45 || strlen($tel) > 10 || strlen($nom_rue) > 45 ||
            strlen($code_postal) > 5 || strlen($ville) > 45 || strlen($pays) > 45 || strlen($categorie) > 45 ||
            !filter_var($mail, FILTER_VALIDATE_EMAIL) || !ctype_digit($code_postal) || !ctype_digit($tel)) {
                //On vérifie que le compte de l'utilisateur existe toujours
                if(!$this->db_comptes->compte_exist($username)){
                    echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                }else{
                    ?><script>alert("Les informations entrées ne respectent pas le format requis")</script><?php
                    $this->load->view('templates/haut_admin');
                    $this->load->view('club/modifier_club', $data);
                    $this->load->view('templates/bas_connectes');
                }
            
            }else{
                //On vérifie que le compte de l'utilisateur existe toujours
                if(!$this->db_comptes->compte_exist($username)){
                    echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                }else{
                    // Modifie les informations du club
                    $this->db_club->update_infos_club($nom, $mail, $tel, $nom_rue, $code_postal, $ville, $pays, $categorie, $a_propos);

                    //Puis redirige l'administrateur vers la page de son profil
                    redirect(base_url()."index.php/administrateur/profil/");
                }

            }

        }

    }

}