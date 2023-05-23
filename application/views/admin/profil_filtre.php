<!--
Nom du fichier: all_rpofils.php
Auteur: Julie STEPHANT
Date de création: 20/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Page d'un ou plusieurs profils qui ont été recherchés à l'aide de la barre de recherche pour l'admin
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
            <a class="site-btn" href=<?php echo base_url(). "index.php/administrateur/list_profils/"; ?>><span class="arrow_carrot-left"></span> Retour</a> 
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
                                //Si le rôle de la personne est administrateur (et que ce n'est pas le comte de la personne connectée), alors on peut baisser son rôle à professeur
                                if($role == "administrateur" && $username != $p['pseudo']){
                                    echo("<a class='little-site-btn' href=" . base_url(). 'index.php/administrateur/diminuer_role_filtre/' . $p['pseudo'] . '/' . $prenom."><span class='arrow_carrot-down'></a>");
                                //Si le rôle de la personne est professeur, alors on peut baisser son rôle à membre ou l'augmenter à admin
                                }else if($role == "professeur"){
                                    echo("<a class='little-site-btn' href=" . base_url(). 'index.php/administrateur/diminuer_role_filtre/' . $p['pseudo'] . '/' . $prenom."><span class='arrow_carrot-down'></a>");
                                    echo("<a class='little-site-btn' href=" . base_url(). 'index.php/administrateur/augmenter_role_filtre/' . $p['pseudo'] . '/' . $prenom."><span class='arrow_carrot-up'></a>");
                                //Si le rôle de la personne est membre, alors on peut augmenter son rôle à professeur
                                }else if($role == "membre"){
                                    echo("<a class='little-site-btn' href=" . base_url(). 'index.php/administrateur/augmenter_role_filtre/' . $p['pseudo'] . '/' . $prenom."><span class='arrow_carrot-up'></a>");
                                }
                                echo("</h5>");
                            echo("</td>");

                            //Action
                            echo("<td>");

                                //On affiche pas ces boutons pour l'admin connecté
                                if($username != $p['pseudo']){
                                    //Même un admin ne peut pas supprimer ou désactiver le compte du président
                                    if($role != 'président'){

                                        if($p['pfl_etat'] == 'A'){
                                            echo("<a class='site-btn' href=" . base_url(). 'index.php/administrateur/activer_desactiver_filtre/' . $p['pseudo'] . '/' . $prenom.">Désactiver</a>");
                                        }else{
                                            echo("<a class='site-btn' href=" . base_url(). 'index.php/administrateur/activer_desactiver_filtre/' . $p['pseudo'] . '/' . $prenom.">Activer</a>");
                                        }
                                        ?><a class="site-btn" href=<?php echo base_url(). "index.php/administrateur/supprimer_compte_filtre/" . $p['pseudo'] . "/" . $prenom; ?> onclick="return confirm('Vous vous apprêtez à supprimer le compte de <?php echo $p['pseudo'] ?>, êtes-vous sûr ?');">Supprimer</a><?php
                                
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