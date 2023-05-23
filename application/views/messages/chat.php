<!--
Nom du fichier: chat.php
Auteur: Julie STEPHANT
Date de création: 09/05/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page du chat entre 2 personnes
-->

<head>
    <script>
        // Fonction de rafraîchissement de la page
        function refreshPage() {
            // Vérifier si le champ de saisie est en cours d'édition
            var inputField = document.getElementById('mymessage');
            if (inputField !== document.activeElement) {
                // Vérifier si la barre de défilement est en bas
                var messageContainer = document.getElementById('chat_messages');
                if (messageContainer.scrollTop + messageContainer.clientHeight === messageContainer.scrollHeight) {
                    // Rafraîchir la page si la barre de défilement est en bas
                    location.reload();
                }
            }
        }

        // Actualiser la page toutes les 5 secondes
        setInterval(refreshPage, 5000);
    </script>
</head>



<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

//pseudo de l'utilisateur connecté
$username =  $this->session->userdata('username');

// Définir le fuseau horaire sur "Europe/Paris"
date_default_timezone_set('Europe/Paris');
//date et heure actuelle
$now = date('d/m/Y'); //date en format jj/mm/aaaa

    if(isset($pseudo_destinataire)){
?>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
                                <div class="card card-bordered">

                                    <div class="card-header" id="haut">
<?php
                                        //Affichage du pseudo du destinataire
                                        ?><h4 class="card-title"><strong><?php echo $pseudo_destinataire->cpt_pseudo; ?></strong></h4><?php         
?>
                                    </div>
                                    <div class="ps-container ps-theme-default ps-active-y" id="chat_messages" style="overflow-y: scroll !important; height:400px !important;">                                   
<?php
                                        //S'il n'y a aucun messages dans la conversation on affiche "Début de conversation avec X"
                                        if($messages == NULL){
?>
                                            <br /><br /><br /><br /><br /><br /><br />
                                            <CENTER><h3> Début de votre conversation avec <?php echo $pseudo_destinataire->cpt_pseudo; ?></h3></CENTER>
                                            <br /><br /><br /><br /><br /><br />
<?php
                                        }else{
                                            
                                            $date_prec = null;
                                            //Pour chaque message de la conversation
                                            foreach($messages as $message){

                                                // récupération de la date du message
                                                $date_courante = $message['date_envoi'];

                                                // comparaison avec la date précédente
                                                if($date_courante != $date_prec){

                                                    //si la date n'est pas aujourd'hui, on l'affiche sous forme jj/mm/aaaa
                                                    if($now != $date_courante){
                                                        // affichage de la date
                                                        echo '<div class="media media-meta-day">' . $date_courante . '</div>';
                                                    }else{
                                                        // affichage d' "Ajourd'hui"
                                                        echo '<div class="media media-meta-day">Aujourd\'hui</div>';
                                                    }
                                                    // stockage de la nouvelle date
                                                    $date_prec = $date_courante;
                                                }
?>
                                                <!-- Messages du destinataire -->
                                                <div class="media media-chat" id = "<?php $message['mes_id'] ?>">
<?php
                                                    //Si le destinataire est l'auteur du message, on affiche sa photo de profil
                                                    if($pseudo_destinataire->cpt_pseudo == $message['pseudo_expediteur']){
?>
                                                        <img class="avatar" src=<?php echo base_url()."style/img/pp/" . $pseudo_destinataire->pfl_pp?> alt="">
<?php
                                                    }
?>
                                                <div class="media-body">
<?php
                                                    //Si le destinataire est l'auteur du message, on en affiche le contenu et l'heure du message
                                                    if($pseudo_destinataire->cpt_pseudo == $message['pseudo_expediteur']){
?>
                                                        <p><?php echo nl2br($message['mes_contenu']) ?></p>
                                                        <p class="meta"><time><?php echo $message['heure_envoi']; ?></time></p>
<?php     
                                                    }                                         
?>
                                                </div>
                                                </div>

                                                <!-- Messages de la personne connectée -->
                                                <div class="media media-chat media-chat-reverse" id = "<?php $message['mes_id'] ?>">
                                                <div class="media-body">
<?php  
                                                        //Si l'utilisateur connecté est l'auteur des messages, on en affiche le contenu
                                                        if($username == $message['pseudo_expediteur']){
?>
                                                            <p><?php echo nl2br($message['mes_contenu']) ?></p>
                                                            <p class="meta">
                                                                <time><?php echo $message['heure_envoi'];?></time>
                                                                <a href="<?php echo base_url(). "index.php/messages/supprimer/" . $message['mes_id'] . "/" . $pseudo_destinataire->cpt_id; ?>" onclick="return confirm('Vous vous apprêtez à supprimer votre message, êtes-vous sûr ?');"><i class="fa fa-trash"></i></a></li>
                                                            </p>
<?php
                                                        }
?>
                                                </div>
                                                </div>
<?php 
                                            } 
?>
                                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;"></div></div></div>
<?php
                                        }
?>
                                            <!-- Input -->
                                            <?php echo form_open('messages/afficher_conversation/' . $id_destinataire);?>
                                            <div class="publisher bt-1 border-light">
                                                <textarea id="mymessage" class="publisher-input" name="message_chat" type="text" placeholder="Ecrire..." required="required"></textarea>
                                                <button type="submit" class="publisher-btn"><i class="fa fa-paper-plane"></i></button>
                                            </div>
                                            </form>
<?php 
                                            //Si le champ de saisie est vide, le message "the field message_chat is required" s'affiche
                                            echo validation_errors();                                 
    } 
?>
                                    </div>

                                    <!-- Pour qu'a chaque rechargement de la page, la barre de défilement reste en bas pour le conteneur de la conversation
                                    (important de le mettre après la dite div et non avant (ne fonctionne pas sinon)) -->
                                    <script>
                                        document.getElementById("chat_messages").scrollTop = document.getElementById("chat_messages").scrollHeight;
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>