<!--
Nom du fichier: Accueil.php
Auteur: Julie STEPHANT
Date de création: 12/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Accueil pour la gestion de la page d'accueil
-->

<?php
class Accueil extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_club');
        $this->load->helper('url_helper');
    }

    // Affiche la page d'accueil avec les informations du club
    public function afficher(){
        $data['info_club'] = $this->db_club->get_info_club();

        $this->load->view('templates/haut_accueil');
        $this->load->view('accueil/page_accueil', $data);
        $this->load->view('templates/bas_accueil');
    }
}
?>