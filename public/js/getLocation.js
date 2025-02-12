document.addEventListener("DOMContentLoaded", () => {
    const storedLocation = localStorage.getItem("userLocation");
    if (storedLocation) {

        const { latitude, longitude } = JSON.parse(storedLocation);
        sendLocationToServer(latitude, longitude);

    } else if ("geolocation" in navigator) {

        navigator.geolocation.getCurrentPosition(
            (position) => {

                const { latitude, longitude } = position.coords;
                localStorage.setItem("userLocation", JSON.stringify({ latitude, longitude }));
                sendLocationToServer(latitude, longitude);
            },
            (error) => {

                console.error("Erreur de géolocalisation :", error.message);
                alert("Nous n'avons pas pu récupérer votre localisation.");
            }
        );
    } else {
        alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
    }
});

function sendLocationToServer(latitude, longitude) {
    fetch('/home', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ latitude, longitude })
    })
        .then(response => response.text())
        .then(html => {
            document.querySelector('#nearby-places-section').innerHTML = html;
        })
        .catch(error => console.error("Erreur réseau :", error));
}
