function openFullscreen(img) {
    // Créer un élément d'image
    var fullImg = document.createElement("img");
    // Définir la source de l'image sur l'URL de l'attribut data-setbg de l'image cliquée
    fullImg.src = img.getAttribute("data-setbg");
    // Créer un élément de div pour contenir l'image en plein écran
    var div = document.createElement("div");

    div.style.width = "100%";
    div.style.height = "100%";
    div.style.position = "fixed";
    div.style.top = "0";
    div.style.left = "0";
    div.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
    div.style.zIndex = "9999";
    div.style.display = "flex";
    div.style.justifyContent = "center";
    div.style.alignItems = "center";
    
    //Pour que l'image prenne maximum 90% de la largeur et de la hauteur de l'écran
    fullImg.style.maxWidth = "90%";
    fullImg.style.maxHeight = "90%";

    
    // Ajouter l'image à la div
    div.appendChild(fullImg);
    // Ajouter la div à la page
    document.body.appendChild(div);
    // Ajouter un écouteur d'événements "click" à la div pour fermer la fenêtre en plein écran lorsque l'utilisateur clique dessus
    div.addEventListener("click", function() {
      document.body.removeChild(div);
    });
  }

  
  