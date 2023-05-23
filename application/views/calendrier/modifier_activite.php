<!--
Nom du fichier: modifier_activite.php
Auteur: Julie STEPHANT
Date de création: 24/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de modification d'une activité
-->

<?php 
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}

$username = $this->session->userdata('username');

echo validation_errors(); 

if(isset($activite)) {
?>
<section class="product spad">
    <div class="container">
        
        <div class="section-title">
            <h5>Modifier l'activité</h5>
        </div>

        
        <!-- Formulaire de modification d'une activité -->
        <div class="anime__details__form login__form">
            <?php echo form_open('calendrier/modifier_activite/' . $activite->act_id); ?>

                <div class="col-sm-5">
                    <div class="input__item">
                        <input type="text" name="intitule" value= "<?php echo $activite->act_intitule ?>" maxlength="45" required="required">
                        <span class="icon_book"></span>
                    </div>
                    <div class="input__item">
                        <input type="text" name="lieu" value= "<?php echo $activite->act_lieu ?>" maxlength="45" required="required">
                        <span class="icon_map"></span>
                    </div>
                    <textarea name="description" required="required" ><?php echo $activite->act_description ?></textarea>
                    <label class="label-date" for="date_debut">Date et heure de debut</label>
                    <input type="datetime-local" name="date_debut" value= "<?php echo $activite->act_date_debut ?>" required="required">
                    <br />
                    <br />
                    <label class="label-date" for="date_fin">Date et heure de fin</label>
                    <input type="datetime-local" name="date_fin" value= "<?php echo $activite->act_date_fin ?>" required="required">
                    <br />
                    <br />
<?php
                    //Si l'utilisateur est le président ou un administrateur il peut choisir l'auteur de l'activité
                    if($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){
?>                        
                        <label class="label-date" for="auteur">Qui assurera l'activité?</label>
                        <select name="auteur">
<?php
                            foreach($prof_admin as $pa){
?>
                                <option value="<?php echo $pa['cpt_pseudo']; ?>"><?php echo $pa['cpt_pseudo']; ?></option>
<?php
                            }
?>
                        </select>
                        <br />
                        <br />
                        <br />
<?php
                    }
?>
                    <div class="button-group">
                        <button type="submit" class="site-btn ">Modifier</button>
                        <a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/calendrier/afficher_connectes/"; ?>">Annuler</a>
                    </div>
                </div>

            </form>
        </div>

    </div>
</section>

<?php
}
?>