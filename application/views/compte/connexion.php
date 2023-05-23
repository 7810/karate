<!--
Nom du fichier: connexion.php
Auteur: Julie STEPHANT
Date de création: 12/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de connexion
-->

<?php echo validation_errors(); ?>

<!-- Login Section Begin -->
<section class="login spad">
    <div class="container">
        <div class="row">

            <!-- Formulaire de connexion -->
            <div class="col-lg-6">
                <div class="login__form">
                    <h3>Connexion</h3>

                    <?php echo form_open('compte/connecter'); ?>

                        <div class="input__item">
                            <input type="text" name="pseudo" placeholder="Pseudo *" required="required">
                            <span class="icon_profile"></span>
                        </div>

                        <div class="input__item">
                            <input type="password" name="mdp" placeholder="Mot de passe *" required="required">
                            <span class="icon_lock"></span>
                        </div>

                        <button type="submit" class="site-btn">Connexion</button>

                    </form>
                </div>
            </div>

            <!-- On affiche l'adresse e-mail du président pour celles et ceux qui souhaiteraient s'inscrire au club -->
            <div class="col-lg-6">
                <div class="login__register">
                    <CENTER><h3>Vous souhaitez vous inscrire au club?</h3>
                    <h4> Faites une demande à l'adresse suivante </h4></CENTER>
                    <br />
                    <br />
                    <?php
                    if(isset($president_mail)){
                        ?><CENTER><h4><a href="mailto:<?php echo $president_mail->pfl_mail;?>?subject=Demande d'inscription au club de karaté wado ryu queven"><?php echo $president_mail->pfl_mail; ?></a></h4></CENTER><?php
                    }
                    ?>
                </div>
            </div>
            
        </div>
    </div>
</section>
    <!-- Login Section End -->
