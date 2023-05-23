<!--
Nom du fichier: inscription.php
Auteur: Julie STEPHANT
Date de création: 19/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire d'inscription d'un nouveau membre
-->

<?php 
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

//Si l'utilisateur connecté est un membre ou un professeur, on le redirige vers la page de son profil
if($this->session->userdata('role') == 'M') {
    redirect(base_url()."index.php/membre/profil");
}else if($this->session->userdata('role') == 'P') {
    redirect(base_url()."index.php/professeur/profil");
}

echo validation_errors(); ?>

<section class="login spad">
    <div class="container">

        <!-- Formulaire d'inscription d'un nouveau membre -->
        <div class="login__form">
            <h3>Inscription d'un membre</h3>

            <?php echo form_open('compte/inscription'); ?>

                <div class="input__item">
                    <input type="text" name="pseudo" placeholder="Pseudo *" maxlength="45" required="required">
                    <span class="icon_profile"></span>
                </div>

                <div class="input__item">
                    <input type="text" name="prenom" placeholder="Prénom *" maxlength="45" required="required">
                    <span class="icon_profile"></span>
                </div>

                <div class="input__item">
                    <input type="text" name="nom" placeholder="Nom *" maxlength="45" required="required">
                    <span class="icon_profile"></span>
                </div>

                <div class="input__item">
                    <input type="email" name="mail" placeholder="Adresse e-mail *" maxlength="45" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="exemple: xavierdupond@gmail.com" required="required">
                    <span class="icon_mail_alt"></span>
                </div>

                <div class="input__item">
                    <input type="password" name="mdp" placeholder="Mot de passe *" maxlength="40"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Requis au minimum: un chiffre, une majuscule, une minuscule, 8 caractères" required="required">
                    <span class="icon_lock"></span>
                </div>

                <div class="input__item">
                    <input type="password" name="conf_mdp" placeholder="Confirmation du mot de passe *" maxlength="64" required="required">
                    <span class="icon_lock"></span>
                </div>

                <div class="button-group">
                    <button type="submit" class="site-btn">Inscrire</button>
<?php 
                    if($this->session->userdata('role') == 'D' || $this->session->userdata('role') == 'A'){
                        ?><a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/administrateur/list_profils/"; ?>">Annuler</a><?php
                    }else{
                        ?><a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/professeur/list_profils/"; ?>">Annuler</a><?php
                    }
?>
                </div>

            </form>
        </div>

    </div>
</section>