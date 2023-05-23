<!--
Nom du fichier: commentaires.php
Auteur: Julie STEPHANT
Date de création: 20/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page affichant tous les commentaires d'une publication donnée et un formulaire pour rajouter un commentaire
-->

<?php 
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

//pseudo de la personne connectée
$username = $this->session->userdata('username');
//role de la personne connectée
$role = $this->session->userdata('role');

echo validation_errors(); ?>

<section class="product spad">
    <div class="container">
        <div class="row">

<?php
            if(isset($publi)) { 
?>
                <div class="col-lg-8">
                    <div class="trending__product">
                        <!-- nombre de commentaires -->
                        <div class="section-title">
                            <h5><?php echo $nb_comment->nb_comment?> commentaire(s)</h5>
                        </div>
<?php
                        //S'il n'y a pas encore de commentaires sous la publication donnée
                        if($commentaires == NULL){
                            echo("<h4>Il n'y a pas encore de commentaire sous cette publication</h4>");
                        }

                        if(isset($nb_comment)){
                            //Pour chaque commentaire de la publication
                            foreach($commentaires as $com){
?>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="anime__details__review" id="<?php echo $com['com_id'] ?>">
                                            <div class="anime__review__item">
                                                <div class="anime__review__item__pic">
                                                    <img src=<?php echo base_url()."style/img/pp/" . $com['pfl_pp'] ?> alt="">
                                                </div>
                                            
                                                <!-- On affiche le pseudo de l'auteur du commentaire et il y a combien de temps qu'il a été publié -->
                                                <div class="anime__review__item__text">
                                                    <h6><?php echo $com['pseudo'] ?> - <span><?php echo $com['temps'] ?></span></h6>
                                                    <!-- Puis on affiche son contenu -->
                                                    <!-- (nl2br() permet de convertir les '\n' du contenu du commentaire en balise <br /> ce qui permet de bien afficher les retours à la ligne) -->
                                                    <p><?php echo nl2br($com['com_contenu']) ?></p>

                                                    <br />
                                                    <div class = "blog__details__comment__item__text"> 
<?php
                                                        //Si le commentaire est celui de la personne connectée on affiche le bouton modifier et supprimer
                                                        if($username == $com['pseudo']){
?>
                                                            <a href="<?php echo base_url(). "index.php/commentaire/modifier_com/" . $com['com_id'] . "/" . $publi->pbl_id; ?>"><i class="fa fa-pen"></i></a>
                                                            <a href="<?php echo base_url(). "index.php/commentaire/supprimer_com/" . $com['com_id'] . "/" . $publi->pbl_id; ?>" onclick="return confirm('Vous vous apprêtez à supprimer votre commentaire, êtes-vous sûr ?');"><i class="fa fa-trash"></i></a>
<?php
                                                        }else{

                                                            //Si l'utilisateur connecté est un admin alors dans tous les cas on affiche un bouton supprimer
                                                            if($role == 'A' || $role == 'D'){
                                                                ?><a href="<?php echo base_url(). "index.php/commentaire/supprimer_com/" . $com['com_id'] . "/" . $publi->pbl_id; ?>" onclick="return confirm('Vous vous apprêtez à supprimer le commentaire de <?php echo $com['pseudo'] ?>, êtes-vous sûr ?');"><i class="fa fa-trash"></i></a><?php
                                                            }

                                                            //Si l'utilisateur connecté est un professeur et que le role de l'utilisateur ayant posté le commentaire est membre
                                                            //On affiche un bouton supprimer
                                                            if($role == 'P' && $com['pfl_role'] == 'M'){
                                                                ?><a href="<?php echo base_url(). "index.php/commentaire/supprimer_com/" . $com['com_id'] . "/" . $publi->pbl_id; ?>" onclick="return confirm('Vous vous apprêtez à supprimer le commentaire de <?php echo $com['pseudo'] ?>, êtes-vous sûr ?');"><i class="fa fa-trash"></i></a><?php
                                                            }

                                                        }
?>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
<?php                   
                            }
                        }
?>          
                    </div>
                </div>
<?php            
            
?>
                <!-- Formulaire pour laisser un commentaire -->
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="product__sidebar">
                        <div class="product__sidebar__view">
                                <div class="section-title">
                                    <h5>Laissez un commentaire</h5>
                                </div>
                            <div class="anime__details__form">
                                <?php echo form_open('publication/afficher_1_publi' . '/' . $publi->pbl_id); ?>
                                    <textarea name="commentaire" required="required" placeholder="Votre commentaire"></textarea>
                                    <button type="submit"><i class="fa fa-location-arrow"></i> Commenter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
<?php
            }
?>                        
            <br />

        </div>
    </div>
</section>