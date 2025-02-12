import {LeafIcon} from "./objetcs/Icon.js";
import {categoryIcons} from "./categoryIcons.js";

// Get coordinates from the HTML element that we will use to center the map
const coordinatesElement = document.querySelector("#coordinates");
let latitude = parseFloat(coordinatesElement.dataset.latitude);
let longitude = parseFloat(coordinatesElement.dataset.longitude);
let zoom = 16;

if (isNaN(latitude) && isNaN(longitude)) {
    latitude = 49.25;
    longitude = 4.0333;
    zoom = 13;
}

let map = L.map('map').setView([latitude, longitude], zoom);

let Jawg_Streets = L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', {
    attribution: '<a href="https://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    minZoom: 0,
    maxZoom: 40,
    accessToken: 'uEbi9BXnUOY9p9bOMfqbunp6uai7xWz7CLzv4v75m1OlbAjXuM38PjGDD6uUJcGF'
});

const placesInformations = JSON.parse(document.querySelector("#places").dataset.places);
console.log(placesInformations);

// Iterate over each place in the placesInformations array
placesInformations.forEach(place => {

    const url = '/place/' + place.id;
    const iconUrl = categoryIcons[place.categoryName]
    console.log(iconUrl);
    // const pinIcon = new LeafIcon({ className: 'custom-div-icon', html: iconUrl });
    const icon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color: ${iconUrl['color']}" class="marker-pin"></div>${iconUrl['html']}`,
        iconSize: [30, 42],
        iconAnchor: [15, 42]
    });

    // Create a new Leaflet marker at the specified longitude and latitude
    const marker = L.marker({lat: place.latitude, lng: place.longitude}, {icon: icon}, { title: place.name });

    // Bind a popup to the marker with the place's name, description, and a link to its details
    marker.bindPopup(`
        <div>
            <h1>${place.name}</h1>
            <p>${place.description}</p>
            <a href="${url}">Plus de détails</a>
        </div>
    `, { maxWidth: 260 }).openPopup();

    // Add the marker to the map
    marker.addTo(map);
});

Jawg_Streets.addTo(map);