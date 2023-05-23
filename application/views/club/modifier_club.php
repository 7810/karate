<!--
Nom du fichier: modifier_club.php
Auteur: Julie STEPHANT
Date de création: 24/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de modification des information du club
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

$username = $this->session->userdata('username');

echo validation_errors(); 

if(isset($infos)) {
?>
<section class="product spad">
    <div class="container">
        
        <div class="section-title">
            <h5>Modifier les informations du club</h5>
        </div>

        <!-- Formulaire de modification des informations du club -->
        <div class="anime__details__form login__form">
            <?php echo form_open(''); ?>

                <div class="col-sm-5">
                
                    <label class="label-date" for="nom">Nom du club</label>
                    <div class="input__item">
                        <input type="text" name="nom" value= "<?php echo $infos->clu_nom ?>" maxlength="45" required="required">
                        <span class="icon_book"></span>
                    </div>

                    <label class="label-date" for="mail">E-mail</label>
                    <div class="input__item">
                        <input type="email" name="mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="exemple: xavierdupond@gmail.com" value= "<?php echo $infos->clu_mail ?>" maxlength="45" required="required">
                        <span class="icon_mail"></span>
                    </div>

                    <label class="label-date" for="tel">Téléphone</label>
                    <div class="input__item">
                        <input type="text" name="tel" pattern="[0-9]{10}" title="exemple: 0609090909" value= "<?php echo $infos->clu_telephone ?>" maxlength="10" required="required">
                        <span class="icon_phone"></span>
                    </div>

                    <label class="label-date" for="nom_rue">Nom de la rue</label>
                    <div class="input__item">
                        <input type="text" name="nom_rue" value= "<?php echo $infos->clu_nom_rue ?>" maxlength="45" required="required">
                        <span class="icon_map"></span>
                    </div>

                    <label class="label-date" for="code_postal">Code postal</label>
                    <div class="input__item">
                        <input type="text" maxlength="5" pattern="[0-9]{5}" title="exemple: 29600" name="code_postal" value= "<?php echo $infos->clu_code_postal ?>" maxlength="5" required="required">
                        <span class="icon_map"></span>
                    </div>

                    <label class="label-date" for="ville">Ville</label>
                    <div class="input__item">
                        <input type="text" name="ville" value= "<?php echo $infos->clu_ville ?>" maxlength="45" required="required">
                        <span class="icon_map"></span>
                    </div>

                    <label class="label-date" for="pays">Pays</label>
                    <div class="input__item">
                        <input type="text" name="pays" value= "<?php echo $infos->clu_pays ?>" maxlength="45" required="required">
                        <span class="icon_map"></span>
                    </div>

                    <label class="label-date" for="categorie">Catégorie</label>
                    <div class="input__item">
                        <input type="text" name="categorie" maxlength="45" value= "<?php echo $infos->clu_categorie ?>" maxlength="45" required="required">
                        <span class="icon_tool"></span>
                    </div>

                    <label class="label-date" for="a_propos">À propos</label>
                    <textarea name="a_propos" required="required" ><?php echo $infos->clu_a_propos ?></textarea>

                    <button type="submit" class="site-btn ">Modifier</button>
                    
                </div>

            </form>
        </div>

    </div>
</section>

<?php
}
?>