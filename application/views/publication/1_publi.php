<!--
Nom du fichier: 1_publi.php
Auteur: Julie STEPHANT
Date de création: 19/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page pour afficher une seule publication
-->
<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

//Role de la personne connectée
$role = $this->session->userdata('role');
?>

<section class="product spad">
    <div class="container">

<?php
        if(isset($publi)) {

            if($role == 'A' || $role == 'D'){
                ?><a class="little-site-btn" href=<?php echo base_url(). "index.php/publication/afficher_admin/"; ?>><span class="arrow_carrot-left"></span>Retour</a><?php
            }else if($role == 'P'){
                ?><a class="little-site-btn" href=<?php echo base_url(). "index.php/publication/afficher_prof/"; ?>><span class="arrow_carrot-left"></span>Retour</a><?php
            }else{
                ?><a class="little-site-btn" href=<?php echo base_url(). "index.php/publication/afficher_membre/"; ?>><span class="arrow_carrot-left"></span>Retour</a><?php
            }

            echo("<br />");
            echo("<br />");

            //Pour la traduction de l'état
            $etat = "";
            if($publi->pbl_etat == 'P'){
                $etat = "Publique";
            }else{
                $etat = "Privée";
            }
            
            echo("<CENTER>");
            if($publi->pbl_img != NULL){ //si la publication a un média
                                               
                // Si la publication a une image
                if(str_contains($publi->pbl_img, ".gif") ||
                str_contains($publi->pbl_img, ".png") ||
                str_contains($publi->pbl_img, ".jpg") ||
                str_contains($publi->pbl_img, ".jpeg") ){
?>          
                    <!-- affichage de l'image et du pseudo de l'utilisateur ayant posté la publication -->
                    <!-- openFullscreen permet de l'afficher en pleins écran quand l'image est cliquée -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="product__item">
                            <div class="col-lg-8">
                                <div class="product__item__pic set-bg" data-setbg=<?php echo base_url()."style/img/publications/". $publi->pbl_img?> 
                                id="photo" onclick="openFullscreen(this)">
                                    <div class="ep"><?php echo($publi->pseudo) ?> a publié</div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php
                // Si la publication a une vidéo
                }else if(str_contains($publi->pbl_img, ".mkv") ||
                str_contains($publi->pbl_img, ".mp4") ||
                str_contains($publi->pbl_img, ".avi") ||
                str_contains($publi->pbl_img, ".mov") ){
?>
                    <!-- affichage de la vidéo et du pseudo du l'utilisateur l'ayant posté -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="product__item__text">
                            <h6> <?php echo($publi->pseudo); ?> a publié </h6>
                        </div>
                        <br />
                        <div class="anime__video__player">
                            <!-- class="w-100" est là pour que la vidéo soit responsive -->
                            <video class="w-100"  playsinline controls>
                                <source src=<?php echo base_url()."style/img/publications/" . $publi->pbl_img?> />
                            </video>
                        </div>
                    </div>
<?php
                // Sinon le média m'a pas un format autorisé
                }else{
                    echo("<p> Problème d'affichage </p>");
                    echo("<br />");
                    echo("<p> Le média n'a pas la bonne extension </p>");
                }

            }else{
?>              
                <!-- pseudo de l'utilisateur ayant posté la publication -->
                <div class="product__item__text">
                    <h6> <?php echo($publi->pseudo); ?> a écrit </h6>
                </div>
<?php
            }
?>
            <div class="product__item__text">
                <!-- description de la publication -->
                <!-- (nl2br() permet de convertir les '\n' en balise <br /> ce qui permet de bien afficher les retours à la ligne) -->
                <h5> <?php echo(nl2br($publi->pbl_description)); ?> </h5>

                <!-- like et commentaires -->
                <div class="heart"><i class="fa fa-heart"></i> <?php echo($nb_like->nb_like);?></div>
                <div class="comment"><i class="fa fa-comment"></i> <?php echo($nb_comment->nb_comment);?></div>

                <!-- date de la publication -->
                <ul>
                    <li>Le <?php echo($publi->date_publi); ?></li>
                    <li>à <?php echo($publi->heure_publi); ?></li>
                </ul>

                <!-- état de la publication -->
                <div class="heart"><?php echo($etat);?></div>
            </div>
<?php
        // Si la publication recherchée n'existe pas alors on prévient l'utilisateur
        }else{
?>
            <CENTER><h3>La publication que vous demandez à voir n'existe pas</h3></CENTER>
<?php            
        }
?>

    </div>
</section>