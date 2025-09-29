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

    // 4. Activer le bouton cliqué (celui qui a appelé la fonction)
    // Nous utilisons 'event.currentTarget' pour cibler le bouton
    // qui a déclenché l'événement 'onclick'.
    // NOTE: Si vous utilisez l'attribut 'onclick' comme dans l'exemple HTML,
    // vous devez modifier la fonction pour accepter l'événement 'e' :
    /*
    // Si vous changez le onclick en : onclick="showSection('usersSection', event)"
    function showSection(sectionId, event) {
        ...
        event.currentTarget.classList.add("active");
    }
    */
    
    // Simplifié : Puisque la fonction est appelée via onclick, 
    // l'élément cliqué est 'this' (le bouton). Nous allons juste ajouter la classe 'active'
    // à tous les boutons qui correspondent à la section que nous venons d'afficher.
    // Une façon plus simple est de cibler directement le bouton correspondant à la section affichée :
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