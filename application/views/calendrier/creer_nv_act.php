<!--
Nom du fichier: creer_nv_act.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de création d'une nouvelle activité
-->

<?php 
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

// Si l'utilisateur connecté n'est pas un admin ou un prof on le redirige vers son profil
if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}

echo validation_errors(); 
?>

<section class="product spad">
    <div class="container">
        
        <div class="section-title">
            <h5>Créer une activité</h5>
        </div>
        
        <!-- Formulaire de création d'une activité -->
        <div class="anime__details__form login__form">
            <?php echo form_open('calendrier/creer_activite'); ?>

                <div class="col-sm-5">
                    <div class="input__item">
                        <input type="text" name="intitule" placeholder="Intitulé *" maxlength="45" required="required">
                        <span class="icon_book"></span>
                    </div>
                    <div class="input__item">
                        <input type="text" name="lieu" placeholder="Lieu *" maxlength="45" required="required">
                        <span class="icon_map"></span>
                    </div>
                
                    <textarea name="description" required="required" placeholder="Description *"></textarea>
                
                    <label class="label-date" for="date_debut">Date et heure de debut *</label>
                    <input type="datetime-local" name="date_debut" required="required">
                    <br />
                    <br />
                    <label class="label-date" for="date_fin">Date et heure de fin *</label>
                    <input type="datetime-local" name="date_fin" required="required">
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
                        <button type="submit" class="site-btn ">Creer</button>
                        <a class="site-btn cancel-btn" href= "<?php echo base_url(). "index.php/calendrier/afficher_connectes/"; ?>">Annuler</a>
                    </div>
                </div>

            </form>
        </div>

    </div>
</section>