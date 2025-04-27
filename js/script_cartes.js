document.addEventListener("DOMContentLoaded", function() {
      
    // Chargement des cartes depuis votre API JSON
    fetch("../json/API_cartes.json")
      .then(response => response.json())
      .then(data => {
        const container = document.querySelector(".card-container");
        container.innerHTML = ""; // On vide le contenu existant
        
        data.cartes.forEach(carte => {
          const cardDiv = document.createElement("div");
          cardDiv.classList.add("card");

          // On ajoute un attribut data-rarete à la carte pour le filtrage
          // On suppose que l'objet carte possède une propriété "rarete"
          // Pensez à mettre la valeur en minuscule pour uniformiser
          cardDiv.setAttribute("data-rarete", carte.Rareté.toLowerCase());

          // Générer dynamiquement les statistiques (on exclut image, nom et rarete)
          let statsHTML = "";
          for (let key in carte) {
            if (key !== "img" && key !== "Nom" && key !== "Rareté") { 
              statsHTML += `<p><strong>${key.replace(/_/g, ' ')} :</strong> ${carte[key]}</p>`;
            }
          }

          // Structure de la carte en deux colonnes
          cardDiv.innerHTML = `
                      <div class="col" ontouchstart="this.classList.toggle('hover');">
                          <div class="container">
                              <div class="front" style="background-image: url(${carte.img})">
                                  <div class="inner2">
                                      <h3>${carte.Nom}</h3>
                                      <span>${carte.Rareté}</span>
                                  </div>
                              </div>
                              <div class="back">
                                <div class="back-image" style="background-image: url(${carte.img})"></div>
                                <div class="inner">
                                 ${statsHTML}
                                </div>
                              </div>
                          </div>
                      </div>
              `;
              container.appendChild(cardDiv);
          });
      })
      .catch(error => console.error("Erreur lors du chargement des cartes :", error));


    // Gestion du filtrage
    document.querySelectorAll(".filter-btn").forEach(button => {
      button.addEventListener("click", function(){
        const targetRarete = button.getAttribute("data-filter");
        const cards = document.querySelectorAll(".card");
        
        cards.forEach(card => {
          // Si "all" est sélectionné ou si la rareté correspond, on affiche la carte
          if (targetRarete === "all" || card.getAttribute("data-rarete") === targetRarete) {
            card.style.display = "flex";  // On reprend le display flex initial (vu votre layout)
          } else {
            card.style.display = "none";
          }
        });
        updateActiveFilter(button);
      });
    });

    // Mise à jour du bouton actif
    function updateActiveFilter(activeButton) {
      document.querySelectorAll(".filter-btn").forEach(btn => btn.classList.remove("active"));
      activeButton.classList.add("active");
    }
  });