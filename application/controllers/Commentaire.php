<!--
Nom du fichier: Commentaire.php
Auteur: Julie STEPHANT
Date de création: 12/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Commentaire pour la gestion de la suppression et modification des commentaires
-->

<?php
class Commentaire extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_jaimecom');
        $this->load->model('db_publications');
        $this->load->helper('url_helper');
    }

    // Permet de supprimer un commentaire d'une publication donnée
    public function supprimer_com($id_com, $id_publi){

        //Role de l'utilisateur auteur du commentaire
        $role_user_com = $this->db_jaimecom->get_role_user_com($id_com);
        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir supprimer le commentaire d'une personne
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        }else{

            //Si l'utilisateur est un membre qui n'est pas auteur du commentaire qu'il essaie de surpprimer
            //On le redirige vers la publication qu'il regardait sans supprimer le commentaire
            if($this->session->userdata('role') == 'M' && !$this->db_jaimecom->is_user_com_author($username, $id_com)){
                
                redirect(base_url()."index.php/publication/afficher_1_publi" . "/" . $id_publi);

            //Si l'utilisateur est un professeur qui tente de supprimer le commentaire dont l'auteur n'est pas un membre et le commentaire n'est pas le siens
            //On le redirige vers la publication qu'il regardait sans supprimer le commentaire
            }else if($this->session->userdata('role') == 'P' && $role_user_com->pfl_role != 'M' && !$this->db_jaimecom->is_user_com_author($username, $id_com) ){
                
                redirect(base_url()."index.php/publication/afficher_1_publi" . "/" . $id_publi);

            }else{

                //On supprime le commentaire
                $this->db_jaimecom->delete_commentaire($id_com);

                redirect(base_url()."index.php/publication/afficher_1_publi" . "/" . $id_publi);
            }
        }
    }

    // Permet de modifier un commentaire d'une publication donnée
    public function modifier_com($id_com, $id_publi){

        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('com_modif', 'com_modif', 'required');

        $username = $this->session->userdata('username');
        $role = $this->session->userdata('role');

        $data['commentaire'] = $this->db_jaimecom->get_commentaire($id_com, $username, $id_publi);

        if ($this->form_validation->run() == FALSE){
            
            $this->load->view('templates/haut_admin');
            $this->load->view('commentaires/modifier_com', $data);
            $this->load->view('templates/bas_connectes');
            
        }else{

            //On vérifie que le commentaire que l'utilisateur veut modifier existe toujours
            //(il a pu être supprimée entre temps par un autre utilisateur)
            if(!$this->db_jaimecom->com_exist($id_com)){

                echo "<script>alert('Votre commentaire n\'existe plus');</script>";
                if($role == 'A' || $role == 'D'){
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_admin';</script>";
                }else if($role == 'P'){
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_prof';</script>";
                }else{
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_membre';</script>";
                }

            }else{

                $commentaire = htmlspecialchars(addslashes($this->input->post('com_modif')));

                // Modifie le commentaire
                $this->db_jaimecom->update_commentaire($commentaire, $id_com);

                //Puis redirige l'utilisateur vers la page de la publication qu'il regardait sur le commentaire qu'il vient de modifier
                redirect(base_url()."index.php/publication/afficher_1_publi" . "/" . $data['commentaire']->pbl_id .'#' . $id_com);
            }
        }
    }

}