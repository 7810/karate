<!--
Nom du fichier: page_calendrier_admin.php
Auteur: Julie STEPHANT
Date de création: 24/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page d'affichage du calendrier du club côté admin
-->

<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

// Si l'utilisateur connecté n'est pas un admin on le redirige vers son profil
if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}else if($this->session->userdata('role') == 'P'){
    redirect(base_url()."index.php/professeur/profil");
}
?>

<section class="product spad">
    <div class="container">

<?php
        // Définir le fuseau horaire sur "Europe/Paris"
        date_default_timezone_set('Europe/Paris');
        //date et heure actuelle
        $now = date('Y-m-d H:i:s');
?>
        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="section-title d-flex align-items-center">
                <h4 class="mr-3">Calendrier</h4>
                <a class="round-site-btn" href=<?php echo base_url(). "index.php/calendrier/creer_activite/"; ?>> + </a>
            </div>
        </div>
        <br />
        <br />
        <br />

        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="section-title">
                <h5>En cours</h5>
            </div>
        </div>
<?php
        // Variable permettant d'afficher une seule fois le message "Aucune activité en cours/à venir/passée"
        $flag = false;
        //S'il n'y a pas du tout d'activités
        if($activites == NULL){
            if(isset($nb_status_activites)){
                if($nb_status_activites->nb_passees == 0){
                    if(!$flag){
                        echo("<CENTER><h4> Aucune activité en cours </h4></CENTER>");
                        echo("<br />");
                        echo("<br />");
                        echo("<br />");
                        echo("<br />");
                        $flag = true;
                    }
                }
            }
        }
        // Pour chacune des activités
        foreach ($activites as $a) {
            // Si l'activité est en cours alors on l'affiche
            if($a['act_date_debut'] < $now && $a['act_date_fin'] > $now){
?>
                <div class="container-fluid">
                    <div class = "row">

                        <!-- Date de début et de fin -->
                        <div class="col-sm-3 calendar-date" id="<?php echo $a['act_id']?>"> 
                            <?php echo ("À commencé depuis le "); echo $a['date_debut']; echo(" à "); echo $a['heure_debut'];
                            echo("<br />");
                            echo("<br />");
                            echo ("Termine le "); echo $a['date_fin']; echo(" à "); echo $a['heure_fin']; ?>
                        </div>

                        <!-- Titre et lieu -->
                        <div class="col-sm-2 calendar-items"> 
                            <?php echo $a['act_intitule'];

                            // Si l'activité est annulée alors on affiche qu'elle est annulée
                            if($a['act_etat'] == 'X'){
                                echo(" (annulé)");
                            }

                            echo("<br />");
                            echo("<br />");
                            echo("<br />"); 
                            echo $a['act_lieu'];?>
                        </div>

                        <!-- Description -->
                        <div class="col-sm-6 calendar-items"> <?php echo $a['act_description']; ?> </div>

                        <!-- Pseudo de l'auteur -->
                        <div class="col-sm-1 calendar-items"> <?php echo $a['pseudo']; ?> </div>
                    </div>
                </div>
<?php       //Sinon on informe l'utilisateur qu'aucune activité est en cours
            }else{
                if(isset($nb_status_activites)){
                    if($nb_status_activites->nb_en_cours == 0){
                        if(!$flag){
                            echo("<CENTER><h4> Aucune activité en cours </h4></CENTER>");
                            echo("<br />");
                            echo("<br />");
                            $flag = true;
                        }
                    }
                }
            }
        }
?>
        <br />
        <br />
        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="section-title">
                <h5>À venir</h5>
            </div>
        </div>
<?php   
        // Variable permettant d'afficher une seule fois le message "Aucune activité en cours/à venir/passée"
        $flag = false;
        //S'il n'y a pas du tout d'activités
        if($activites == NULL){
            if(isset($nb_status_activites)){
                if($nb_status_activites->nb_passees == 0){
                    if(!$flag){
                        echo("<CENTER><h4> Aucune activité à venir </h4></CENTER>");
                        echo("<br />");
                        echo("<br />");
                        echo("<br />");
                        echo("<br />");
                        $flag = true;
                    }
                }
            }
        }
        // Pour chacune des activités
        foreach ($activites as $a) {
            // Si l'activité est à venir on l'affiche
            if($a['act_date_debut'] > $now && $a['act_date_fin'] > $now){
?>
                <div class="container-fluid">
                    <div class = "row">

                        <!-- Date de début et de fin -->
                        <div class="col-sm-3 calendar-date" id="<?php echo $a['act_id']?>"> 
                            <?php echo ("Commence le "); echo $a['date_debut']; echo(" à "); echo $a['heure_debut'];
                            echo("<br />");
                            echo("<br />");
                            echo ("Termine le "); echo $a['date_fin']; echo(" à "); echo $a['heure_fin']; ?>
                        </div>
                        <!-- Titre et lieu -->
                        <div class="col-sm-2 calendar-items"> 
                            <div class="description-and-buttons">
                                <?php echo $a['act_intitule']; echo(" ");

                                echo("<br />");
                                echo("<br />");
                                echo("<br />"); 
                                echo $a['act_lieu'];
                                ?>

                                <!-- Boutons pour annuler/activer et modifier -->
                                <div class="buttons-container">
                                    <?php 
                                    if($a['act_etat'] == 'X'){ ?>
                                        <a class="little-site-btn" href=<?php echo base_url(). "index.php/calendrier/annuler_active/" . $a['act_id']; ?>> Activer</a>
                                    <?php }else{ ?>
                                        <a class="little-site-btn" href=<?php echo base_url(). "index.php/calendrier/annuler_active/" . $a['act_id']; ?>> Annuler</a>
                                    <?php } ?>
                                    <a class="little-site-btn" href=<?php echo base_url(). "index.php/calendrier/modifier_activite/" . $a['act_id']; ?>><i class="fa fa-pen"></i></a>
                                </div>
                            </div>
                        
                        <!-- Description -->
                        </div>
                        <div class="col-sm-6 calendar-items"> <?php echo $a['act_description']; ?></div>

                        <!-- Pseudo de l'auteur -->
                        <div class="col-sm-1 calendar-items"> <?php echo $a['pseudo']; ?> </div>
                    </div>
                </div>
                <br />
                <br />
<?php       //Sinon on informe l'utilisateur qu'aucune activité est à venir
            }else{
                if(isset($nb_status_activites)){
                    if($nb_status_activites->nb_a_venir == 0){
                        if(!$flag){
                            echo("<CENTER><h4> Aucune activité à venir </h4></CENTER>");
                            echo("<br />");
                            echo("<br />");
                            $flag = true;
                        }
                    }
                }
            }
        }
?>
        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="section-title">
                <h5>Passées</h5>
            </div>
        </div>
<?php
        // Variable permettant d'afficher une seule fois le message "Aucune activité en cours/à venir/passée"
        $flag = false;
        //S'il n'y a pas du tout d'activités
        if($activites == NULL){
            if(isset($nb_status_activites)){
                if($nb_status_activites->nb_passees == 0){
                    if(!$flag){
                        echo("<CENTER><h4> Aucune activité passée </h4></CENTER>");
                        echo("<br />");
                        echo("<br />");
                        echo("<br />");
                        echo("<br />");
                        $flag = true;
                    }
                }
            }
        }
        // Pour chacune des activités
        foreach ($activites as $a) {
            // Si l'activité est passée et active alors on l'affiche
            if($a['act_date_debut'] < $now && $a['act_date_fin'] < $now && $a['act_etat'] == 'A'){
?>
                <div class="container-fluid">
                    <div class = "row">

                        <!-- Date de fin -->
                        <div class="col-sm-3 calendar-date" id="<?php echo $a['act_id']?>"> 
                            <?php echo ("Terminé depuis le "); echo $a['date_fin']; echo(" à "); echo $a['heure_fin']; ?>
                        </div>

                        <!-- Titre et lieu -->
                        <div class="col-sm-2 calendar-items"> 
                            <?php echo $a['act_intitule'];

                            // Si l'activité a été annulée alors on affiche qu'elle a été annulée
                            if($a['act_etat'] == 'X'){
                                echo(" (annulé)");
                            }

                            echo("<br />");
                            echo("<br />");
                            echo("<br />");  
                            echo $a['act_lieu'];?>
                        </div>

                        <!-- Description -->
                        <div class="col-sm-6 calendar-items"> <?php echo $a['act_description']; ?> </div>

                        <!-- Pseudo de l'auteur -->
                        <div class="col-sm-1 calendar-items"> <?php echo $a['pseudo']; ?> </div>
                    </div>
                </div>
                <br />
                <br />
<?php       //Sinon on informe l'utilisateur qu'aucune activité est passée
            }else{
                if(isset($nb_status_activites)){
                    if($nb_status_activites->nb_passees == 0){
                        if(!$flag){
                            echo("<CENTER><h4> Aucune activité passée </h4></CENTER>");
                            echo("<br />");
                            echo("<br />");
                            echo("<br />");
                            echo("<br />");
                            $flag = true;
                        }
                    }
                }
            }
        }
?>
    </div>
</section>