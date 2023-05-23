<!--
Nom du fichier: all_profils.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page de la liste de tous les profils pour le professeur
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

echo validation_errors(); 
?>

<section class="product spad">
    <div class="container">

        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="section-title">
                <h4>Liste des profils</h4>
            </div>
        </div>
        
        <div class = "d-flex align-items-center">
            <h4 class="mr-3">
<?php
            // Affiche le nombre de comptes inscrit au club
            if(isset($nb_comptes)){
                echo $nb_comptes->nb_comptes;
            }
?>
            personnes dans le club
            </h4>
        </div>
        <br />
        <br />
        <br />

<?php
        // S'il n'y a aucun comptes d'inscrit dans la base
        // (Ne peut pas arriver car accessible qu'à partir d'un compte autre que membre)
        if($profils == NULL){
?>
            <CENTER>
            <h3>Il n'y a aucun compte dans le club</h3>
            </CENTER>
            <br />
<?php
        }else{

            //pseudo de la personne connectée
            $username = $this->session->userdata('username');
?>
            <div class="header__right">
                <a href="#" class="search-switch"><span class="icon_search"></span> Rechercher une personne par son prénom/pseudo</a> 
            </div>

            <!-- Affichage des informations des différents profils dans un tableau -->
            <table class="table table-hover">
                <thead>
                    <tr class = "highlighted-row">
                        <th><h4>Pseudo</h4></th>
                        <th><h4>Prénom</h4></th>
                        <th><h4>Nom</h4></th>
                        <th><h4>Mail</h4></th>
                        <th><h4>Rôle</h4></th>
                        <th><h4>Action</h4></th>
                    </tr>
                </thead>
                <tbody>
<?php
                    $role = "";
                    // Pour chaque profils existant
                    foreach($profils as $p){

                        //Pour traduire le rôle
                        if($p['pfl_role'] == 'D'){
                            $role = "président";
                        }else if($p['pfl_role'] == 'A'){
                            $role = "administrateur";
                        }else if($p['pfl_role'] == 'P'){
                            $role = "professeur";
                        }else{
                            $role = "membre";
                        }

                        
                        echo("<tr class='default-row'>");
                            //Pseudo prenom nom mail
                            echo ("<td id='$p[cpt_id]'>" . "<h5>" . $p['pseudo'] . "</h5>" . "</td>" .
                                "<td>" . "<h5>" . $p['pfl_prenom'] . "</h5>" . "</td>" .
                                "<td>" . "<h5>" . $p['pfl_nom'] . "</h5>" . "</td>" . 
                                "<td>" . "<h5>" . $p['pfl_mail'] . "</h5>" . "</td>"
                                );

                            //Role    
                            echo("<td>");
                                echo("<h5>");
                                echo $role;
                                // Un professeur ne peut qu'augmenter le rôle d'un membre
                                //Si le rôle de la personne est membre, alors on peut baisser son rôle à professeur
                                if($role == "membre"){
                                    echo("<a class='little-site-btn' href=" . base_url(). 'index.php/professeur/augmenter_role/' . $p['pseudo'] . "><span class='arrow_carrot-up'></a>");
                                }
                                echo("</h5>");
                            echo("</td>");

                            //Action
                            echo("<td>");

                                //On n'affiche pas ces boutons pour le professeur connecté ni pour les comptes autre que membre
                                if($username != $p['pseudo']){
                                    if($role == "membre"){

                                        if($p['pfl_etat'] == 'A'){
                                            echo("<a class='site-btn' href=" . base_url(). 'index.php/professeur/activer_desactiver/' . $p['pseudo'] . ">Désactiver</a>");
                                        }else{
                                            echo("<a class='site-btn' href=" . base_url(). 'index.php/professeur/activer_desactiver/' . $p['pseudo'] . ">Activer</a>");
                                        }
                                        ?><a class="site-btn" href=<?php echo base_url(). "index.php/professeur/supprimer_compte/" . $p['pseudo']; ?> onclick="return confirm('Vous vous apprêtez à supprimer le compte de <?php echo $p['pseudo'] ?>, êtes-vous sûr ?');">Supprimer</a><?php
                                
                                    }
                                }

                            echo("</td>");
                        echo("</tr>");
                    }
?>
                </tbody>
            </table>
<?php
        }
?>
    <!-- Formulaire pour rechercher une personne grâce à son prénom -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch"><i class="icon_close"></i></div>
            <?php echo form_open('professeur/list_profils', array('class' => 'search-model-form')); ?>
                <input type="text" name="search" id="search-input" placeholder="Rechercher une personne...." required="required">
            </form>
        </div>
    </div>

    </div>
</section>