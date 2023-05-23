<!--
Nom du fichier: haut_admin.php
Auteur: Julie STEPHANT
Date de création: 14/04/23
//_____________________________________________
//_____________________________________________
DESCRIPTION:
Template haut de la page de l'espace privé des administrateurs
-->

<!DOCTYPE html>
<html lang="zxx">


<!-- Pour changer l'icone de l'onglet -->
<link rel="shortcut icon" href=<?php echo base_url()."style/img/favicon.png"?> type="image/x-icon">
<link rel="icon" href=<?php echo base_url()."style/img/favicon.png"?> type="image/x-icon">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="karate queven">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karaté Wado ryu | Queven</title>

    <!-- Inclusion des fichiers CSS et JS de la librairie select2 pour un dropdown avec tous les pays du monde -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Css Styles -->
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/bootstrap.min.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/elegant-icons.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/font_awesome.min.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/nice-select.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/owl.carousel.min.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/plyr.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/slicknav.min.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/style_chat.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url();?>style/css/style.css' />
    
    <!-- Pour changer l'icone de l'onglet -->
    <link rel="icon" type="image/png" href=<?php echo base_url()."style/img/favicon.png"?>>
</head>

<body>

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">

                <!-- logo -->
                <div class="col-lg-2">
                    <div class="header__logo">
                        <img src=<?php echo base_url()."style/img/logo_queven.png"?>  width="80" height="80" alt="">
                    </div>
                </div>

                <!-- nav -->
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li class="active"><a href=<?php echo base_url()."index.php/administrateur/profil/"?>>Mon profil</a></li>
                                <li><a href=<?php echo base_url(). "index.php/administrateur/list_profils/"; ?>>Tous les profils</a></li>
                                <li><a href=<?php echo base_url(). "index.php/publication/afficher_admin/"; ?>>Publications</a></li>
                                <li><a href=<?php echo base_url(). "index.php/calendrier/afficher_connectes/"; ?>>Calendrier</a></li>
                                <li><a href=<?php echo base_url(). "index.php/club/modifier/"; ?>>Club</a></li>
                                <li ><a href=<?php echo base_url(). "index.php/messages/afficher/"; ?>>Messages</a></li>
                                <li ><a href= <?php echo base_url(). "index.php/compte/deconnexion/"; ?> >Déconnexion</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
            <div id="mobile-menu-wrap"></div>
        </div>
    </header>
    <!-- Header End -->