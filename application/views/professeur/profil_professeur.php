<!--
Nom du fichier: profil_professeur.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page du profil du professeur connecté
-->

<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

// Si l'utilisateur connecté n'est pas un professeur on le redirige vers son profil
if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}else if($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){
    redirect(base_url()."index.php/administrateur/profil");
}

if(isset($info_profil)){
?>

    <section class="product spad">
        <div class="container">
        
            <CENTER><h3>Bienvenue sur votre compte professeur</h3> <h6> <?php echo $info_profil->cpt_pseudo; ?> </h6></CENTER>
            <br />
            <br />

            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="section-title d-flex align-items-center">
                    <h4 class="mr-3">Mes informations</h4>
                    <a class="site-btn" href=<?php echo base_url(). "index.php/professeur/modifier_profil/"; ?>>Modifier</a>
                </div>
            </div>

            <!-- Affichage des informations personnelles de l'utilisateur connecté -->
            <div class="anime__review__item">
                <div class="anime__review__item__pic">
                    <img src=<?php echo base_url()."style/img/pp/" . $info_profil->pfl_pp?> alt="">
                    <a class="little-site-btn" href=<?php echo base_url(). "index.php/compte/modifier_pp/"; ?>>modifier ma photo de profil</a>
                </div>
            </div>  
<?php
            echo ("<h4>");
            echo ("Mon pseudo: "); echo $info_profil->cpt_pseudo;
            echo("<br />");
            echo("Mon nom: "); echo $info_profil->pfl_prenom; echo(" "); echo $info_profil->pfl_nom;
            echo("<br />");
            echo("Mon mail: "); echo $info_profil->pfl_mail;
            echo("<br />");
            echo("Mon rôle: Professeur");
            echo ("</h4>");
            echo("<br />");
?>
        </div>
    </section>

<?php
}
?>