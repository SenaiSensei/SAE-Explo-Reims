const removeItinerary = async (itinerary) => {
    const response = await fetch('/itinerary/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({itinerary: itinerary})
    });

    if (response.ok) {
        let element = document.getElementById(`item-${itinerary}`);
        element.remove();
    }
};

function showAlert(itinerary) {
    let alertBox = document.getElementById("modalOverlay");
    alertBox.classList.remove("hidden");
    document.getElementById("okBtn").dataset.itinerary = itinerary;
    const fenetreNode = document.createElement('div')
    fenetreNode.id = 'menu-cote'
    fenetreNode.className = 'fixed top-0 left-0 h-full w-full z-30'
    fenetreNode.style.backgroundColor = 'rgba(100,100,100,0.5)';
    fenetreNode.addEventListener('click', fermerMenu)
    document.body.appendChild(fenetreNode)
}

document.getElementById("cancelBtn").onclick = function() {
    let alertBox = document.getElementById("modalOverlay");
    const fenetreNode = document.getElementById('menu-cote')
    fenetreNode.remove()
    alertBox.classList.add("hidden");
}

document.getElementById("okBtn").onclick = async function () {
    let alertBox = document.getElementById("modalOverlay");
    const fenetreNode = document.getElementById('menu-cote')
    fenetreNode.remove()
    alertBox.classList.add("hidden");
    await removeItinerary(document.getElementById("okBtn").dataset.itinerary);
}

const fermerMenu = () => {
    const fenetreNode = document.getElementById('menu-cote')
    let alertBox = document.getElementById("modalOverlay");
    fenetreNode.remove()
    alertBox.classList.add("hidden");
}