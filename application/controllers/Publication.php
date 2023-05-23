<!--
Nom du fichier: Publication.php
Auteur: Julie STEPHANT
Date de création: 12/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Controller Publication pour la gestion de la page des publication
-->

<?php
class Publication extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('db_publications');
        $this->load->model('db_jaimecom');
        $this->load->model('db_comptes');
        $this->load->helper('url_helper');
    }

    // Affiche toutes les publications publiques
    public function afficher(){
        $data['publications'] = $this->db_publications->get_all_publi_publiques();
        $data['like'] = array(); //tableau pour stocker le nombre de like de chaque publication
        $data['comment'] = array(); //tableau pour stocker le nombre de commentaires de chaque publication

        foreach ($data['publications'] as $publication) {
            $id = $publication['pbl_id']; //id de la publication
            //nb de like et commentaires qu'elle a
            $comment_count = $this->db_jaimecom->get_count_comment($id);
            $like_count = $this->db_jaimecom->get_count_like($id);
            //stocké dans les tableaux
            $data['like'][$id] = $like_count;
            $data['comment'][$id] = $comment_count;
        }

        $this->load->view('templates/haut_accueil');
        $this->load->view('publication/page_publi', $data);
        $this->load->view('templates/bas_accueil');
    }

    // Affiche toutes les publications du côté administrateur
    public function afficher_admin(/*$offset = 0*/){
        $data['publications'] = $this->db_publications->get_all_publi(/*9, $offset*/);
        $data['like'] = array(); //tableau pour stocker le nombre de like de chaque publication
        $data['comment'] = array(); //tableau pour stocker le nombre de commentaires de chaque publication
        $data['like_yes_no'] = array(); //tableau pour stocker la valeur de si une publication a été likée par l'utilisateur connecté
        //$data['offset'] = $offset;

        //pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        foreach ($data['publications'] as $publication) {
            $id = $publication['pbl_id']; //id de la publication

            //nb de like et commentaires qu'elle a
            $comment_count = $this->db_jaimecom->get_count_comment($id);
            $like_count = $this->db_jaimecom->get_count_like($id);

            //Vrai si l'utilisateur connecté a déjà aimé la publication, faux sinon
            $aimer = $this->db_jaimecom->get_like_person($username, $id);

            //stocké dans les tableaux
            $data['like'][$id] = $like_count;
            $data['comment'][$id] = $comment_count;
            $data['like_yes_no'][$id] = $aimer;
        }

        $this->load->view('templates/haut_admin');
        $this->load->view('publication/page_publi_admin', $data);
        $this->load->view('templates/bas_connectes');
    }

    // Affiche toutes les publications du côté administrateur
    public function afficher_prof(){
        $data['publications'] = $this->db_publications->get_all_publi();
        $data['like'] = array(); //tableau pour stocker le nombre de like de chaque publication
        $data['comment'] = array(); //tableau pour stocker le nombre de commentaires de chaque publication
        $data['like_yes_no'] = array(); //tableau pour stocker la valeur de si une publication a été likée par l'utilisateur connecté

        //pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        foreach ($data['publications'] as $publication) {
            $id = $publication['pbl_id']; //id de la publication

            //nb de like et commentaires qu'elle a
            $comment_count = $this->db_jaimecom->get_count_comment($id);
            $like_count = $this->db_jaimecom->get_count_like($id);

            //Vrai si l'utilisateur connecté a déjà aimé la publication, faux sinon
            $aimer = $this->db_jaimecom->get_like_person($username, $id);

            //stocké dans les tableaux
            $data['like'][$id] = $like_count;
            $data['comment'][$id] = $comment_count;
            $data['like_yes_no'][$id] = $aimer;
        }

        $this->load->view('templates/haut_prof');
        $this->load->view('publication/page_publi_prof', $data);
        $this->load->view('templates/bas_connectes');
    }

    //Affiche toutes les publications du côté membre
    public function afficher_membre(){

        $data['publications'] = $this->db_publications->get_all_publi();
        $data['like'] = array(); //tableau pour stocker le nombre de like de chaque publication
        $data['comment'] = array(); //tableau pour stocker le nombre de commentaires de chaque publication
        $data['like_yes_no'] = array(); //tableau pour stocker la valeur de si une publication a été likée par l'utilisateur connecté

        //pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        foreach ($data['publications'] as $publication) {
            $id = $publication['pbl_id']; //id de la publication

            //nb de like et commentaires qu'elle a
            $comment_count = $this->db_jaimecom->get_count_comment($id);
            $like_count = $this->db_jaimecom->get_count_like($id);

            //Vrai si l'utilisateur connecté a déjà aimé la publication, faux sinon
            $aimer = $this->db_jaimecom->get_like_person($username, $id);

            //stocké dans les tableaux
            $data['like'][$id] = $like_count;
            $data['comment'][$id] = $comment_count;
            $data['like_yes_no'][$id] = $aimer;
        }

        $this->load->view('templates/haut_membre');
        $this->load->view('publication/page_publi_membre', $data);
        $this->load->view('templates/bas_connectes');
    }

    // Affiche une seule publication dont l'id est connu, avec ses commentaires et un formulaire pour pouvoir ajouter un commentaire
    public function afficher_1_publi($id){

        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('commentaire', 'commentaire', 'required');

        $data['publi'] = $this->db_publications->get_one_publi($id);
        $data['nb_comment'] = $this->db_jaimecom->get_count_comment($id);
        $data['nb_like'] = $this->db_jaimecom->get_count_like($id);
        $data['commentaires'] = $this->db_jaimecom->get_all_com_publi($id);

        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');
        //Rôle de l'utilisateur connecté
        $role = $this->session->userdata('role');

        if ($this->form_validation->run() == FALSE){

            if($role == 'D' || $role == 'A'){
                $this->load->view('templates/haut_admin');
            }else if($role == 'P'){
                $this->load->view('templates/haut_prof');
            }else{
                $this->load->view('templates/haut_membre');
            }
            $this->load->view('publication/1_publi', $data);
            $this->load->view('publication/commentaires', $data);
            $this->load->view('templates/bas_connectes');
            
        }else{

            //On vérifie que la publication que l'utilisateur veut commenter existe toujours
            //(elle a pu être supprimée entre temps par un autre utilisateur)
            if(!$this->db_publications->publi_exist($id)){

                echo "<script>alert('Cette publication n\'existe plus');</script>";
                if($role == 'A' || $role == 'D'){
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_admin';</script>";
                }else if($role == 'P'){
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_prof';</script>";
                }else{
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_membre';</script>";
                }

            //On vérifie que le compte de l'utilisateur existe toujours
            }else if(!$this->db_comptes->compte_exist($username)){
                echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
            }else{

                $commentaire = htmlspecialchars(addslashes($this->input->post('commentaire')));

                //Insert le nouveau commentaire de l'utilisateur connecté dans la base
                $this->db_jaimecom->insert_commentaire($username, $id, $commentaire);

                //Puis redirige l'utilisateur vers la page de la publication qu'il regardait
                redirect(base_url()."index.php/publication/afficher_1_publi" . "/" . $id);
            }
        }       
    }

    //ajoute le like de l'utilisateur connecté à une publication dont l'id est connu
    public function like($pseudo, $id){

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir liker une publication à la place d'une autre personne
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        }else{
            //Role de la personne connectée
            $role = $this->session->userdata('role');

            // Si un utilisateur essaie de liker une publication en usurpant le pseudo de quelqu'un d'autre
            // On le redirige vers sa page des publications sans ajouter le like
            if(strtoupper($pseudo) != strtoupper($this->session->userdata('username'))){

                if($role == 'D' || $role == 'A'){
                    redirect(base_url()."index.php/publication/afficher_admin");
                }else if($role == 'P'){
                    redirect(base_url()."index.php/publication/afficher_prof");
                }else{
                    redirect(base_url()."index.php/publication/afficher_membre");
                }

            }else{
                //On vérifie que le compte de l'utilisateur existe toujours
                if(!$this->db_comptes->compte_exist($pseudo)){

                    echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                
                //On vérifie que la publication existe toujours
                }else if(!$this->db_publications->publi_exist($id)){
                    
                    echo "<script>alert('Cette publication n'existe plus');</script>";
                    if($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){
                        echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_admin';</script>";
                    }else if($this->session->userdata('role') == 'P'){
                        echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_prof';</script>";
                    }else{
                        echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_membre';</script>";
                    }
                
                }else{
                    
                    //Ajoute le like dans la base
                    $this->db_jaimecom->insert_like($pseudo, $id);

                    //Redirige l'utilisateur selon son rôle
                    if($role == 'D' || $role == 'A'){
                        redirect(base_url()."index.php/publication/afficher_admin#".$id);
                    }else if($role == 'P'){
                        redirect(base_url()."index.php/publication/afficher_prof#".$id);
                    }else{
                        redirect(base_url()."index.php/publication/afficher_membre#".$id);
                    }
                
                }

            }
        }    
    }

    //Supprime le like de l'utilisateur connecté d'une une publication dont l'id est connu
    public function delete_like($pseudo, $id){

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir supprimer le like d'une personne
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        }else{
            //Role de la personne connectée
            $role = $this->session->userdata('role');

            // Si un utilisateur essaie de supprimer le like de quelqu'un d'autre sur une publication
            // On le redirige vers sa page des publication sans supprimer le like
            if(strtoupper($pseudo) != strtoupper($this->session->userdata('username'))){

                if($role == 'D' || $role == 'A'){
                    redirect(base_url()."index.php/publication/afficher_admin");
                }else if($role == 'P'){
                    redirect(base_url()."index.php/publication/afficher_prof");
                }else{
                    redirect(base_url()."index.php/publication/afficher_membre");
                }

            }else{

                //On vérifie que le compte de l'utilisateur existe toujours
                if(!$this->db_comptes->compte_exist($pseudo)){

                    echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                
                //On vérifie que la publication existe toujours
                }else if(!$this->db_publications->publi_exist($id)){
                    
                    echo "<script>alert('Cette publication n'existe plus');</script>";
                    if($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){
                        echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_admin';</script>";
                    }else if($this->session->userdata('role') == 'P'){
                        echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_prof';</script>";
                    }else{
                        echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_membre';</script>";
                    }
                
                }else{

                    //Supprime le like
                    $this->db_jaimecom->delete_like($pseudo, $id);

                    //Et redirige l'utilisateur selon son rôle
                    if($role == 'D' || $role == 'A'){
                        redirect(base_url()."index.php/publication/afficher_admin#".$id);
                    }else if($role == 'P'){
                        redirect(base_url()."index.php/publication/afficher_prof#".$id);
                    }else{
                        redirect(base_url()."index.php/publication/afficher_membre#".$id);
                    }
                }
            }
        }        
    }

    //Supprime une publication dont on connait l'id de la base ET du dossier img/publications
    public function supprimer($id){

        //Si l'utilisateur n'est pas connecté, on l'empêche de pouvoir supprimer une publication
        if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {

            redirect(base_url()."index.php/compte/connecter");

        //Si l'utilisateur est un membre, on l'empêche de pouvoir supprimer une publication
        }else if($this->session->userdata('role') == 'M'){
          
            redirect(base_url()."index.php/membre/profil");

        }else{
            
            //Si l'utilisateur est un professeur qui n'est pas auteur de la publication qu'il essaie de surpprimer
            //On réaffiche la page des publications sans supprimer la publication en question
            if($this->session->userdata('role') == 'P' && !$this->db_publications->is_user_publi_author($this->session->userdata('username'), $id)){
                
                redirect(base_url()."index.php/publication/afficher_prof");

            }else{

                $role = $this->session->userdata('role');

                //Nom de l'image à supprimer
                $image = $this->db_publications->get_img_publi($id);

                // Suppression du fichier d'image correspondant
                if ($image->pbl_img != null) {
                    $image_path = FCPATH . "style/img/publications/" . $image->pbl_img;
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }

                //Supprime la publication
                $this->db_publications->delete_publi($id);

                //Et redirige l'utilisateur selon son rôle
                if($role == 'D' || $role == 'A'){
                    redirect(base_url()."index.php/publication/afficher_admin");
                }else if($role == 'P'){
                    redirect(base_url()."index.php/publication/afficher_prof");
                }
            }

        }       
    }

    // Permet de modifier une publication dont on connait l'id
    public function modifier($id){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('publi_modif', 'publi_modif', 'required');

        //Role de la personne connectée
        $role = $this->session->userdata('role');
        //Pseudo de la personne connectée
        $username = $this->session->userdata('username');

        $data['publi'] = $this->db_publications->get_one_publi($id);
        $data['nb_comment'] = $this->db_jaimecom->get_count_comment($id);
        $data['nb_like'] = $this->db_jaimecom->get_count_like($id);

        // Si l'utilisateur est un professeur qui n'est pas auteur de la publication qu'il essaie de modifier
        // On le redirige vers la page des publications sans passer par le formulaire de modification
        if($role == 'P' && !$this->db_publications->is_user_publi_author($this->session->userdata('username'), $id)){
            redirect(base_url()."index.php/publication/afficher_prof");
        }

        if ($this->form_validation->run() == FALSE){
            
            if($role == 'A' || $role == 'D'){
                $this->load->view('templates/haut_admin');
            }else{
                $this->load->view('templates/haut_prof');
            }
            $this->load->view('publication/1_publi', $data);
            $this->load->view('publication/modifier', $data);
            $this->load->view('templates/bas_connectes');
            
        }else{

            $description = htmlspecialchars(addslashes($this->input->post('publi_modif')));
            $etat = $this->input->post('etat');

            //On vérifie que la publication que l'utilisateur veut commenter existe toujours
            //(elle a pu être supprimée entre temps par un autre utilisateur)
            if(!$this->db_publications->publi_exist($id)){

                echo "<script>alert('Cette publication n\'existe plus');</script>";
                if($role == 'A' || $role == 'D'){
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_admin';</script>";
                }else if($role == 'P'){
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_prof';</script>";
                }else{
                    echo "<script>window.location.href = '".base_url()."index.php/publication/afficher_membre';</script>";
                }

            //On vérifie que le compte de l'utilisateur existe toujours
            }else if(!$this->db_comptes->compte_exist($username)){
                echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
            }else{

                // Modifie la description de la publication
                $this->db_publications->update_publication($description, $etat, $id);

                //Redirige l'utilisateur vers la page des publications
                if($role == 'A' || $role == 'D'){
                    redirect(base_url()."index.php/publication/afficher_admin");
                }else if($role == 'P'){
                    redirect(base_url()."index.php/publication/afficher_prof");
                }
            }
        }
    }

    //Permet de créer une nouvelle publication
    public function creer_publi(){

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('description', 'description', 'required');

        // Définir le fuseau horaire sur "Europe/Paris"
        // Permettra de mettre un suffixe avec la date et l'heure à laquelle chaque fichier sera téléversé pour s'assurer qu'il soit unique
        date_default_timezone_set('Europe/Paris');

        //Role de la personne connectée
        $role = $this->session->userdata('role');
        //Pseudo de l'utilisateur connecté
        $username = $this->session->userdata('username');

        if ($this->form_validation->run() == FALSE){
            
            if($role == 'A' || $role == 'D'){
                $this->load->view('templates/haut_admin');
            }else{
                $this->load->view('templates/haut_prof');
            }
            $this->load->view('publication/creer');
            $this->load->view('templates/bas_connectes');
            
        }else{

            $description = htmlspecialchars(addslashes($this->input->post('description')));
            $etat = $this->input->post('etat');

            // Chemin absolu du répertoire où le fichier sera téléversé
            // FCPATH spécifie le chemin absolu depuis la racine du projet
            $uploaddir = FCPATH . "style/img/publications/";

            //Nom du fichier qui remplace les espaces du nom par des underscore (sinon problème d'affichage)
            $nom_fichier = str_replace(' ', '_', $_FILES['userfile']['name']);
            // Concaténation du nom de fichier avec la date et l'heure actuelle pour que le nom soit unique
            $filename =  date("YmdHis") . "_" . $nom_fichier;

            // Chemin complet du fichier que l'utilisateur a téléversé + nom et extention du fichier
            $uploadfile = $uploaddir . basename(str_replace(" ", "_", $filename));

            // Si l'utilisateur decide de mettre un média dans sa publication
            if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK) {

                //La liste des types de fichiers étant autorisés
                $types_autorises = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/mkv', 'video/avi', 'video/mov');
                //Le type du fichier que l'utilisateur veut téléverser
                $type_fichier = $_FILES['userfile']['type'];
                    
                //Si l'extension du fichier que l'utilisateur veut téléverser est dans les types autorisés
                if (in_array($type_fichier, $types_autorises)) {

                    //Vérification de la validité du fichier
                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

                        //On vérifie que le compte de l'utilisateur existe toujours
                        if(!$this->db_comptes->compte_exist($username)){
                            echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                            echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                        }else{
                            //Insert la publication avec l'image puis redirige l'utilisateur vers la page des publications
                            $this->db_publications->insert_publication($username, $description, $filename, $etat);
                            if($role == 'A' || $role == 'D'){
                                redirect(base_url()."index.php/publication/afficher_admin");
                            }else{
                                redirect(base_url()."index.php/publication/afficher_prof");
                            }
                        }

                    } else {
                        //Problème lors du téléversement
                        ?><script>alert("Le fichier n’a pas été téléchargé. Il y a eu un problème")</script><?php
                        if($role == 'A' || $role == 'D'){
                            $this->load->view('templates/haut_admin');
                        }else{
                            $this->load->view('templates/haut_prof');
                        }
                        $this->load->view('publication/creer');
                        $this->load->view('templates/bas_connectes');
                    }

                } else {
                    // Le type de fichier n'est pas autorisé
                    ?><script>alert("Ce type de fichier n'est pas autorisé")</script><?php
                    if($role == 'A' || $role == 'D'){
                        $this->load->view('templates/haut_admin');
                    }else{
                        $this->load->view('templates/haut_prof');
                    }
                    $this->load->view('publication/creer');
                    $this->load->view('templates/bas_connectes');
                }

            //Si l'utilisateur ne veut pas mettre d'image dans sa publication
            }else{

                //On vérifie que le compte de l'utilisateur existe toujours
                if(!$this->db_comptes->compte_exist($username)){
                    echo "<script>alert('Votre compte a été supprimé ou désactivé par un autre utilisateur');</script>";
                    echo "<script>window.location.href = '".base_url()."index.php/compte/deconnexion';</script>";
                }else{
                    //Insert la publication sans image puis redirige l'utilisateur vers la page des publications
                    $this->db_publications->insert_publication($username, $description, NULL, $etat);
                    if($role == 'A' || $role == 'D'){
                        redirect(base_url()."index.php/publication/afficher_admin");
                    }else{
                        redirect(base_url()."index.php/publication/afficher_prof");
                    }
                }
                
            }//fin if utilisateur met une image ou non

        }//fin if form rempli

    }

}
?>