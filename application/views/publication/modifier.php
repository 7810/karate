<!--
Nom du fichier: modifier.php
Auteur: Julie STEPHANT
Date de création: 21/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de modification d'une publication
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

echo validation_errors(); 

if(isset($publi)) {
    ?>
    <section class="product spad">
        <div class="container">
            
            <!-- Formulaire de modification de publication -->
            <div class="anime__details__form">
                <?php echo form_open('publication/modifier' . '/' . $publi->pbl_id); ?>

                    <div class="section-title d-flex align-items-center">
                        <h4 class="mr-3">Modifier la publication</h4>

                        <!-- dropdown pour l'état de la publication -->
                        <select id="etat" name="etat">
                            <option value="P">Publique</option>
                            <option value="X">Privée</option>
                        </select>
                    </div>

                    <textarea name="publi_modif" required="required" ><?php echo $publi->pbl_description ?></textarea>
                    <button type="submit"><i class="fa fa-location-arrow"></i> Modifier</button>
                </form>
            </div>
    
        </div>
    </section>
<?php
}
?>