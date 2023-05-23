<!--
Nom du fichier: modifier_com.php
Auteur: Julie STEPHANT
Date de création: 21/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Formulaire de modification d'un commentaire
-->

<?php 
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

$username = $this->session->userdata('username');

echo validation_errors(); 

if(isset($commentaire)) {
?>
<section class="product spad">
    <div class="container">
        
        <div class="section-title">
            <h5>Modifier votre commentaire</h5>
        </div>
        <!-- Formulaire de modification de commentaire -->
        <div class="anime__details__form">
            <?php echo form_open('commentaire/modifier_com' . '/' . $commentaire->com_id . '/' . $commentaire->pbl_id); ?>
                <textarea name="com_modif" required="required" ><?php echo $commentaire->com_contenu ?></textarea>
                <button type="submit"><i class="fa fa-location-arrow"></i> Modifier</button>
            </form>
        </div>

    </div>
</section>

<?php
// Si l'utilisateur cherche à modifier son commentaire à partir d'une autre publication
// ou qu'il cherche à modifier un commentaire dont il n'est pas l'auteur
// ou qu'il cherche à modifier un commentaire qui n'est pas à lui
}else{
?>
    <br /><br />
    <CENTER><h3>Ce commentaire n'existe pas, n'est pas à vous ou est sous une autre publication</h3></CENTER>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
<?php
}
?>