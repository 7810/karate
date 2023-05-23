<?php
if(isset($info_club)){
?>
    <section class="product spad">
        <div class="container">

            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="section-title">
                    <h4><?php echo $info_club->clu_nom; ?> | <?php echo $info_club->clu_categorie; ?></h4>
                </div>
            </div>
            <br />
            <br />
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="section-title">
                    <h5>Coordonées</h5>
                </div>
            </div>

            <!-- Affiche les coordonnées du club -->
            <div class="product__item__text">
                <div class="icon_coordonees"><i class="fa fa-home"></i>
                    <?php echo $info_club->clu_nom_rue; ?>,
                    <?php echo $info_club->clu_code_postal; ?>
                    <?php echo $info_club->clu_ville; ?>,
                    <?php echo $info_club->clu_pays; ?>
                </div>

                <div class="icon_coordonees"><i class="fa fa-phone"></i>
                    <?php echo $info_club->telephone; ?>
                </div>

                <div class="icon_coordonees"><span class="icon_mail_alt"></span>
                    <?php echo $info_club->clu_mail; ?>
                </div>
            </div>

            <br />
            <br />

            <!-- Affiche l'onglet "à propos" du club -->
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="section-title">
                    <h5>À propos</h5>
                </div>
            </div>

            <div class="product__item__text">
                <h5><?php echo nl2br($info_club->clu_a_propos); ?></h5>
            </div>

        </div>
    </section>
<?php
}
?>