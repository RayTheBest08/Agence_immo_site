
// main.js
document.addEventListener('DOMContentLoaded', function(){
  // example: simple client validation could be added here
});

function showSection(sectionId) {
    let i, tabcontent, tabbuttons;

    // 1. Masquer tous les contenus d'onglets
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
        tabcontent[i].classList.remove("active"); // Retirer la classe CSS active
    }

    // 2. Désactiver tous les boutons d'onglets (retirer la classe 'active')
    tabbuttons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabbuttons.length; i++) {
        tabbuttons[i].classList.remove("active");
    }

    // 3. Afficher la section spécifique
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = "block"; // Ou 'flex', selon votre mise en page
        targetSection.classList.add("active");
    }

    const buttonId = "tab" + sectionId.replace('Section', '');
    const activeButton = document.getElementById(buttonId);
    if (activeButton) {
        activeButton.classList.add("active");
    }
}

// Initialisation : Afficher la section 'Annonces' au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // S'assurer qu'une seule section est visible au départ
    showSection('annoncesSection'); 
});