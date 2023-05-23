<!--
Nom du fichier: modifier_profil.php
Auteur: Julie STEPHANT
Date de création: 14/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de modification de profil
-->

<?php 
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

echo validation_errors();

$role = $this->session->userdata('role');

$r = "";
if($role == 'A' || $role == 'D'){
    $r = "administrateur";
}else if($role == 'P'){
    $r = "professeur";
}else{
    $r = "membre";
}

if(isset($info_profil)){
?>

<section class="login spad">
    <div class="container">
        <div class="row">

            <!-- Formulaire de modification de l'utilisateur connecté -->
            <div class="col-lg-6">
                <div class="login__form">
                    <h3>Modifier mon profil</h3>

                    <?php echo form_open($r . '/modifier_profil');?>
                        <div class="input__item">
                            <input type="text" name="pseudo" value= "<?php echo $info_profil->cpt_pseudo ?>" maxlength="45" required="required">
                            <span class="icon_profile"></span>
                        </div>

                        <div class="input__item">
                            <input type="text" name="prenom" value= "<?php echo $info_profil->pfl_prenom ?>" maxlength="45" required="required">
                            <span class="icon_profile"></span>
                        </div>

                        <div class="input__item">
                            <input type="text" name="nom" value= "<?php echo $info_profil->pfl_nom ?>" maxlength="45" required="required">
                            <span class="icon_profile"></span>
                        </div>

                        <div class="input__item">
                            <input type="email" name="mail" value= "<?php echo $info_profil->pfl_mail ?>" maxlength="45" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="exemple: xavierdupond@gmail.com" required="required">
                            <span class="icon_mail_alt"></span>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="site-btn">Modifier</button>
                            <a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/administrateur/profil/"; ?>">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Formulaire de modification du mot de passe de l'utilisateur connecté -->
            <div class="col-lg-6">
                <div class="login__form">
                    <h3>Modifier mon mot de passe</h3>
                    <?php echo form_open($r . '/modifier_mdp'); ?>

                        <div class="input__item">
                            <input type="password" name="mdp" placeholder="Mot de passe actuel *" maxlength="40" required="required">
                            <span class="icon_lock"></span>
                        </div>

                        <div class="input__item">
                            <input type="password" name="nv_mdp" placeholder="Nouveau mot de passe *" maxlength="40" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Requis au minimum: un chiffre, une majuscule, une minuscule, 8 caractères" required="required">
                            <span class="icon_lock"></span>
                        </div>

                        <div class="input__item">
                            <input type="password" name="conf_nv_mdp" placeholder="Confirmation du nouveau mot de passe *" required="required">
                            <span class="icon_lock"></span>
                        </div>
                        
                        <div class="button-group">
                            <button type="submit" class="site-btn">Modifier</button>
                            <a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/administrateur/profil/"; ?>">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>    
    </div>
</section>

<?php
}
?>