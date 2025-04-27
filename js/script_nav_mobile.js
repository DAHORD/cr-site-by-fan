const hamburger = document.getElementById("hamburger-btn");
    const navMenu = document.getElementById("nav-menu");

    hamburger.addEventListener("click", () => {
      if (navMenu.classList.contains("show")) {
        navMenu.classList.remove("show");
        hamburger.innerHTML = "&#9776;"; // Retour à l'icône hamburger
      } else {
        navMenu.classList.add("show");
        hamburger.innerHTML = "&times;"; // Passe en icône de fermeture (croix)
      }
    });

    // Fermer le menu lorsqu'un lien est cliqué (optionnel sur mobile)
    const navLinks = document.querySelectorAll("#nav-menu a");
    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        if (navMenu.classList.contains("show")) {
          navMenu.classList.remove("show");
          hamburger.innerHTML = "&#9776;";
        }
      });
    });