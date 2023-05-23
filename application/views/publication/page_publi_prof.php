<!--
Nom du fichier: page_publi_prof.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page des publications du club sur un compte professeur
-->

<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

// Si l'utilisateur connecté n'est pas un prof on le redirige vers son profil
if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}else if($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){
    redirect(base_url()."index.php/administrateur/profil");
}

// Pseudo de l'utilisateur connecté
$username = $this->session->userdata('username');
?>

<section class="product spad">
    <div class="container">
    <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="section-title d-flex align-items-center">
            <h4 class="mr-3">Publications</h4>
            <a class="round-site-btn" href=<?php echo base_url(). "index.php/publication/creer_publi/"; ?>> + </a>
        </div>
    </div>
    <br />
    <br />
    <br />

<?php
        // Si il n'y a encore aucune publication
        if($publications == NULL){
?>
            <CENTER>
            <h3>Aucune publication pour l'instant, ajoutez-en !</h3>
            </CENTER>
            <br />
<?php
        }else{
?>
            <div class="trending__product">
                <div class="row">
                        
<?php
                    // Pour chacune des publications
                    foreach($publications as $p){
?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <!-- l'id est l'ancre pour pouvoir être redirigé sur la publication en question quand on la like ou qu'on enlève notre like -->
                            <div class="product__item" id="<?php echo $p['pbl_id']?>">                  
<?php
                                if($p['pbl_img'] != NULL){ //si la publication a une image
                                 
                                    // Si la publication a une image
                                    if(str_contains($p['pbl_img'], ".gif") ||
                                    str_contains($p['pbl_img'], ".png") ||
                                    str_contains($p['pbl_img'], ".jpg") ||
                                    str_contains($p['pbl_img'], ".jpeg") ){
?> 
                                        <!-- affichage de l'image et du pseudo de l'utilisateur ayant posté la publication -->
                                        <!-- openFullscreen permet de l'afficher en pleins écran quand l'image est cliquée -->
                                        <div class="col-lg-8">
                                            <div class="product__item__pic set-bg" data-setbg=<?php echo base_url()."style/img/publications/". $p['pbl_img']?> 
                                            id="photo" onclick="openFullscreen(this)">
                                                <div class="ep"><?php echo($p['pseudo']) ?> a publié</div>
                                            </div>
                                        </div>
<?php
                                    //Sinon si la publication a une video
                                    }else if(str_contains($p['pbl_img'], ".mkv") ||
                                    str_contains($p['pbl_img'], ".mp4") ||
                                    str_contains($p['pbl_img'], ".avi") ||
                                    str_contains($p['pbl_img'], ".mov") ){
?>
                                        <!-- pseudo de l'utilisateur ayant posté la publication -->
                                        <div class="product__item__text">
                                            <h6> <?php echo($p['pseudo']); ?> a publié </h6>
                                        </div>
                                        <br />
                                        <div class="anime__video__player">
                                            <!-- class="w-100" est là pour que la vidéo soit responsive -->
                                            <video class="w-100"  playsinline controls>
                                                <source src=<?php echo base_url()."style/img/publications/" . $p['pbl_img']?> />
                                            </video>
                                        </div>
                       
<?php
                                    // Sinon c'est qu'elle n'a pas la bonne extension
                                    }else{
                                        echo("<p> Problème d'affichage </p>");
                                        echo("<br />");
                                        echo("<p> Le média n'a pas la bonne extension </p>");
                                    }

                                }else{
?>                                  
                                    <!-- pseudo de l'utilisateur ayant posté la publication -->
                                    <div class="product__item__text">
                                        <h6> <?php echo($p['pseudo']); ?> a écrit </h6>
                                    </div>
<?php
                                }
?>
                                <div class="product__item__text">

                                    <!-- description de la publication -->
                                    <!-- (nl2br() permet de convertir les '\n' en balise <br /> ce qui permet de bien afficher les retours à la ligne) -->
                                    <h5> <?php echo(nl2br($p['pbl_description'])); ?> </h5>

<?php                               //affichage du bouton like et du nombre de like
                                    foreach ($like as $l => $like_count) {
                                        //On ne veut pas afficher tous les likes de toutes les publications sous chaque publi
                                        //Quand l'id de la publication qu'on affiche match avec l'id dans le tableau like
                                        //On affiche
                                        if($p['pbl_id'] == $l){

                                            foreach($like_yes_no as $lyn => $aimer){

                                                //Si l'utilisateur a déjà aimer la publication
                                                if($aimer){

                                                    //On ne veut pas afficher tous les likes de la publication
                                                    //Quand l'id de la publication qu'on affiche match avec l'id dans le tableau aimer
                                                    if($p['pbl_id'] == $lyn){
?>
                                                        <!-- bouton pour enlever son like -->
                                                        <div class="heart"> 
                                                            <div class = "blog__details__comment__item__text">
                                                                <a href="<?php echo base_url(). "index.php/publication/delete_like/" . $username . "/" . $p['pbl_id']; ?>"><i class="fa fa-heart-broken"></i><?php echo("  " . $like_count->nb_like);?></a> 
                                                            </div> 
                                                        </div>   
<?php
                                                    }

                                                }else{

                                                    //On ne veut pas afficher tous les likes de la publication
                                                    //Quand l'id de la publication qu'on affiche match avec l'id dans le tableau aimer
                                                    if($p['pbl_id'] == $lyn){
?>
                                                    <!-- bouton pour liker -->
                                                    <div class="heart">
                                                        <div class = "blog__details__comment__item__text"> 
                                                            <a href="<?php echo base_url(). "index.php/publication/like/" . $username . "/" . $p['pbl_id']; ?>"><i class="fa fa-heart"></i><?php echo("  " . $like_count->nb_like);?></a> 
                                                        </div> 
                                                    </div>
<?php
                                                    }
                                                }
                                            }                                        
                                        }   
                                    }
                                   
                                    //affichage du bouton comment et du nombre de commentaires
                                    foreach ($comment as $c => $comment_count) {
                                        //On ne veut pas afficher tous les commentaires de toutes les publications sous chaque publi
                                        //Quand l'id de la publication qu'on affiche match avec l'id dans le tableau comment
                                        //On affiche
                                        if($p['pbl_id'] == $c){
?>
                                            <!-- bouton pour commenter -->
                                            <div class="comment">
                                                <div class = "blog__details__comment__item__text"> 
                                                    <a href="<?php echo base_url(). "index.php/publication/afficher_1_publi/" . $p['pbl_id']; ?>"><i class="fa fa-comment"></i><?php echo("  " . $comment_count->nb_comment);?></a> 
                                                </div> 
                                            </div>
<?php                                        
                                        }   
                                    }
?>
                                    <!-- affichage de la date de la publication, du bouton supprimer et du bouton modifier -->
                                    <ul>
                                        <li>Le <?php echo($p['date_publi']); ?></li>
                                        <li>à <?php echo($p['heure_publi']); ?></li>

                                        <!-- Le professeur connecté ne peut supprimer que ses propres publications -->
                                        <?php if($username == $p['pseudo']){?>
                                            <li><a href="<?php echo base_url(). "index.php/publication/supprimer/" . $p['pbl_id']; ?>" onclick="return confirm('Vous vous apprêtez à supprimer votre publication, êtes-vous sûr ?');"><i class="fa fa-trash"></i></a></li>
                                            <li><a href="<?php echo base_url(). "index.php/publication/modifier/" . $p['pbl_id']; ?>"><i class="fa fa-pen"></i></a></li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>
                        </div>
<?php
                    }
?>
                </div>
            </div>        
<?php
        }
?>
    </div>
</section>

<!-- Si l'image est en plein écran et qu'on clique n'importe où, ça la ferme -->
<div id="fullscreen-overlay" onclick="closeFullscreen()">
  <img src="" alt="" id="fullscreen-image">
</div>