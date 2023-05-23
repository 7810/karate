<!--
Nom du fichier: modifier_pp.php
Auteur: Julie STEPHANT
Date de création: 12/05/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de modification de photo de profil
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

?>

<section class="login spad">
    <div class="container">
        <div class="row">

            <!-- Formulaire de modification de l'utilisateur connecté -->
            <div class="anime__details__form">

                <div class="section-title d-flex align-items-center">
                    <h4 class="mr-3">Modifier ma photo de profil</h4>
                </div>

                <?php echo form_open_multipart('compte/modifier_pp'); ?>
                    
                    <!-- taille max du fichier en octets (10000000 o = 10 Mo) pouvant être téléversée -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" /> 
                    <!-- Pour choisir un fichier image-->
                    <input type="file" class = "highlighted-row" accept="image/png, image/jpeg, image/jpg, image/gif" id="file-upload" name="pp_file" ><br>
                    <p>Votre fichier ne doit pas exéder 10 Mo</p>

                    <div class="button-group">
                        <button type="submit" class="site-btn">Modifier</button>
                        <a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/" .$r. "/"."profil/"; ?>">Annuler</a>
                    </div>

                </form>
            </div>
        </div>    
    </div>
</section>