<!--
Nom du fichier: profil_filtre.php
Auteur: Julie STEPHANT
Date de création: 25/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page d'un ou plusieurs profils qui ont été recherchés à l'aide de la barre de recherche pour le professeur
-->

<?php
//Si la session n'est pas ouverte on redirige l'utilisateur vers la page de connexion
if(empty($this->session->userdata('username')) && empty($this->session->userdata('role'))) {
    redirect(base_url()."index.php/compte/connecter");
}

// Si l'utilisateur connecté n'est pas un prof on le redirige vers son profil
if($this->session->userdata('role') == 'M'){
    redirect(base_url()."index.php/membre/profil");
}else if($this->session->userdata('role') == 'A' || $this->session->userdata('role') == 'D'){
    redirect(base_url()."index.php/administrateur/profil");
}
?>

<section class="product spad">
    <div class="container">

        <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="section-title">
                <h4>Recherche d'une personne</h4>
            </div>
        </div>
        <br />
        <br />
        <br />
        <div class="header__right">
            <a class="site-btn" href=<?php echo base_url(). "index.php/professeur/list_profils/"; ?>><span class="arrow_carrot-left"></span> Retour</a> 
        </div>

<?php
        // Si la personne recherchée n'existe pas (ou du moins aucun prénom ne comporte la chaîne de caractère recherchée)
        if($person == NULL){
?>
            <CENTER>
            <h3>La personne que vous recherchez n'existe peut-être pas</h3>
            </CENTER>
            <br />
<?php
        }else{

            //pseudo de la personne connectée
            $username = $this->session->userdata('username');
?>
            <!-- Affichage des informations de la ou des personnes recherhchées dans un tableau -->
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
                    // Pour chaque résulats de la recherche
                    foreach($person as $p){

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
                            echo ("<td>" . "<h5>" . $p['pseudo'] . "</h5>" . "</td>" .
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
                                    echo("<a class='little-site-btn' href=" . base_url(). 'index.php/professeur/augmenter_role_filtre/' . $p['pseudo'] . '/' . $prenom ."><span class='arrow_carrot-up'></a>");
                                }
                                echo("</h5>");
                            echo("</td>");

                            //Action
                            echo("<td>");

                                //On n'affiche pas ces boutons pour le professeur connecté ni pour les comptes autre que membre
                                if($username != $p['pseudo']){
                                    if($role == "membre"){

                                        if($p['pfl_etat'] == 'A'){
                                            echo("<a class='site-btn' href=" . base_url(). 'index.php/professeur/activer_desactiver_filtre/' . $p['pseudo'] . '/' . $prenom . ">Désactiver</a>");
                                        }else{
                                            echo("<a class='site-btn' href=" . base_url(). 'index.php/professeur/activer_desactiver_filtre/' . $p['pseudo'] . '/' . $prenom . ">Activer</a>");
                                        }
                                        ?><a class="site-btn" href=<?php echo base_url(). "index.php/professeur/supprimer_compte_filtre/" . $p['pseudo'] . '/' . $prenom; ?> onclick="return confirm('Vous vous apprêtez à supprimer le compte de <?php echo $p['pseudo'] ?>, êtes-vous sûr ?');">Supprimer</a><?php
                                
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

    </div>
</section>