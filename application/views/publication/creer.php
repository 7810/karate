<!--
Nom du fichier: creer.php
Auteur: Julie STEPHANT
Date de création: 24/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire pour creer une publication
-->

<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

// Si l'utilisateur connecté est un membre on le redirige vers son profil
if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}

// Pseudo de l'utilisateur connecté
$username = $this->session->userdata('username');
// Role de l'utilisateur connecté
$role = $this->session->userdata('role');

echo validation_errors(); 
?>

<section class="product spad">
    <div class="container">
<?php        
        if($role == 'A' || $role == 'D'){
            ?><a class="little-site-btn" href=<?php echo base_url(). "index.php/publication/afficher_admin/"; ?>><span class="arrow_carrot-left"></span>Retour</a><?php
        }else{
            ?><a class="little-site-btn" href=<?php echo base_url(). "index.php/publication/afficher_prof/"; ?>><span class="arrow_carrot-left"></span>Retour</a><?php
        }
?>
        <br />
        <br />
        <br />
        <!-- Formulaire de création de publication -->
        <div class="anime__details__form">
            <?php echo form_open_multipart('publication/creer_publi'); ?>

                <div class="section-title d-flex align-items-center">
                    <h4 class="mr-3">Créer une publication</h4>

                    <!-- dropdown pour l'état de la publication -->
                    <select id="etat" name="etat">
                        <option value="P">Publique</option>
                        <option value="X">Privée</option>
                    </select>
                </div>
                
                <!-- taille max du fichier en octets (100000000 o = 100 Mo) pouvant être téléversée -->
                <input type="hidden" name="MAX_FILE_SIZE" value="100000000" /> 
                <!-- Pour choisir un fichier image ou video-->
                <input type="file" class = "highlighted-row" accept="image/png, image/jpeg, image/jpg, image/gif, video/mp4, video/mkv, video/avi, video/mov" id="file-upload" name="userfile" ><br>
                <p>Votre fichier ne doit pas exéder 100 Mo auquel cas votre publication sera postée sans média</p>
                <br />
                <!-- description -->
                <textarea name="description" required="required" placeholder = "Ecrivez une légende *"></textarea>
                <br />
                <button type="submit"><i class="fa fa-location-arrow"></i> Publier</button>
            </form>
        </div>

    </div>
    </section>