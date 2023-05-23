<!--
Nom du fichier: messages.php
Auteur: Julie STEPHANT
Date de création: 09/05/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page de recherche de conversation avec tous les profils
-->

<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

//Pseudo de l'utilisateur connecté
 $username =  $this->session->userdata('username');
?>

<section class="product spad">
    <div class="container">

        <!-- Content wrapper start -->
        <div class="content-wrapper ">

            <!-- Row start -->
            <div class="row gutters">

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    
                    <div class="card-bordered">

                        <!-- Row start -->
                        <div class="row no-gutters">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
                                <div class="users-container">

                                    <!-- Formulaire de recherche d'un utilisateur -->
                                    <?php echo form_open('messages/afficher');?>
                                        <div class="chat-search-box">
                                            <div class="input-group">
                                                <input name="search" class="form-control" placeholder="Rechercher un utilisateur" required="required">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
<?php
                                    //Si le champ de saisie est vide, le message "the field search is required" s'affiche
                                    echo validation_errors();  
?>
                                    <div class="ps-container ps-theme-default ps-active-y" id="chat-content" style="overflow-y: scroll !important; height:400px !important;">
                                        
                                        <!-- liste des utilisateurs -->
                                        <ul class="users">
<?php
                                            foreach($comptes as $c){
                                                //On n'affiche pas le propre compte de la personne connectée
                                                if($username != $c['pseudo']){
?>
                                                    <li class="person" data-chat="person1">
                                                        <a href=<?php echo base_url(). "index.php/messages/afficher_conversation/" . $c['cpt_id'] . "#haut"; ?>>
                                                            <div class="user">
                                                                <img src=<?php echo base_url()."style/img/pp/" . $c['pfl_pp']?> alt="">
                                                            </div>
                                                            <p class="name-time">
<?php
                                                                ?><span class="name"><?php echo $c['pseudo'] ?></span><?php

                                                                foreach ($date_last_message as $d => $date){
                                                                    //On ne veut pas afficher toutes les dates de toutes les conversations
                                                                    //Si l'id du compte correspond à l'id de la conversation
                                                                    if($c['cpt_id'] == $d){
                                                                        //Si il y a une date du dernier message de la conversation
                                                                        if($date->last_message != NULL){
                                                                            ?><span class="time"><?php echo " " . $date->last_message; ?></span><?php
                                                                        }
                                                                    }
                                                                }

                                                                foreach ($messages_non_lus as $m => $nb_messages_non_lus){
                                                                    //Si l'id du compte correspond à l'id de la conversation
                                                                    if($c['cpt_id'] == $m){
                                                                        //Si le nombre de messages non lus de la conversations est supérieur à 0
                                                                        if($nb_messages_non_lus->nb > 0){
                                                                            ?><div class="user">
                                                                                <span class="status busy"></span>
                                                                            </div><?php
                                                                        }
                                                                    }
                                                                }
?>
                                                            </p>
                                                        </a>
                                                    </li>
<?php
                                                }
                                            }
?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            

