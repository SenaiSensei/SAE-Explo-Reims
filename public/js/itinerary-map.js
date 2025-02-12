// Initialisation de la carte
const map = L.map('map').setView([48.8566, 2.3522], 13); // Coordonnées et zoom initial

let Jawg_Streets = L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', {
    attribution: '<a href="https://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    minZoom: 0,
    maxZoom: 40,
    accessToken: 'uEbi9BXnUOY9p9bOMfqbunp6uai7xWz7CLzv4v75m1OlbAjXuM38PjGDD6uUJcGF'
});

const placesInformations = JSON.parse(document.querySelector("#places").dataset.places);
console.log(placesInformations);

const bounds = L.latLngBounds([]);
placesInformations.forEach(place => {
    bounds.extend([place.latitude, place.longitude]);
    const url = '/place/' + place.id;

    // Create a new Leaflet marker at the specified longitude and latitude
    const marker = L.marker({lat: place.latitude, lng: place.longitude}, { title: place.name });

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

map.fitBounds(bounds, { padding: [20, 20] });
Jawg_Streets.addTo(map);