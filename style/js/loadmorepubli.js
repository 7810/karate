function loadMorePublications() {
  var start = $('.product__list').children().length; // position de départ pour la requête SQL
  $.ajax({
    url: '<?php echo base_url(); ?>mon_controller/load_more_publications',
    type: 'post',
    data: {start: start},
    dataType: 'json',
    success: function(response) {
      var publications = response.publications;
      var html = '';
      for (var i = 0; i < publications.length; i++) {
        // création de l'élément div pour chaque publication
        var publication = publications[i];
        html += '<div class="product__item">';
        // affichage de la publication
        ...
        html += '</div>';
        // ajout de la publication à la fin de la div #additional-publications
        document.getElementById('additional-publications').appendChild($(html)[0]);
      }
    }
  });
}
