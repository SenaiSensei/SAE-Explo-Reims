// Get coordinates from the HTML element

const placeElement = document.querySelector("#place-coords");
const latitude = parseFloat(placeElement.dataset.latitude);
const longitude = parseFloat(placeElement.dataset.longitude);

// Map initialization
const map = L.map('map', {
    center: [latitude, longitude],
    zoom: 13,
    dragging: false,
    zoomControl: false,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    touchZoom: false
});

// S'assurer que le DOM est prêt avant d'initialiser la carte
document.addEventListener('DOMContentLoaded', () => {
    map.invalidateSize(); // Met à jour les dimensions de la carte
});

let Jawg_Streets = L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', {
    attribution: '<a href="https://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    minZoom: 0,
    maxZoom: 40,
    accessToken: 'uEbi9BXnUOY9p9bOMfqbunp6uai7xWz7CLzv4v75m1OlbAjXuM38PjGDD6uUJcGF'
});

// Create a new Leaflet marker at the specified longitude and latitude
const marker = L.marker({lat: latitude, lng: longitude});

// Add the marker to the map
marker.addTo(map);

// Add the tile layer to the map
Jawg_Streets.addTo(map);
