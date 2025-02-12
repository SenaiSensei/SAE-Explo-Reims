//fermer quand touche une autre partie de l'ecran
const fermerMenu = () => {
    const input = document.getElementById('menu-cb')
    input.checked = false
    const fenetreNode = document.getElementById('menu-cote')
    fenetreNode.remove()
    changerEtatMenu()
}

const changerEtatMenu = () => {
    // Récupérer la case à cocher
    const input = document.getElementById('menu-cb')
    // Récupérer l'état de la case
    const actif = input.checked
    let menu_nav = document.getElementById('menu-nav')
    console.log(actif)
    // Si le menu est affiché
    if (actif) {
        menu_nav.style.display = "flex";
        menu_nav.style.transform = "translateX(0)";
        //element pour regarder le reste de la page
        const fenetreNode = document.createElement('div')
        fenetreNode.id = 'menu-cote'
        fenetreNode.className = 'fixed top-0 left-0 h-full w-full'

        // Ecouter lorsque le visiteur clique dessus
        fenetreNode.addEventListener('click', fermerMenu)

        // Ajouter l'élément à la page
        document.body.appendChild(fenetreNode)
    } else {
        menu_nav.style.display = "none";
        menu_nav.style.transform = "translateX(-100%)";
    }
}
const input = document.getElementById('menu-cb')
document.getElementById('menu-nav').style.display = "none";
document.getElementById('menu-nav').style.transform = "translateX(-100%)";
input.addEventListener('click', changerEtatMenu)
