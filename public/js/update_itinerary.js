document.addEventListener('DOMContentLoaded', () => {
    const placesList = document.getElementById('places-list');

    placesList.addEventListener('click', async (e) => {
        if (e.target.classList.contains('add-button')) {
            const placeId = e.target.dataset.id;
            const position = document.getElementById(`pos-input-${placeId}`).value;
            const itineraryId = e.target.dataset.itinerary;
            await addPlace(itineraryId, placeId, position, document.getElementById(`item-${placeId}`).querySelector('.add-button'),
                document.getElementById(`pos-input-${placeId}`));
        } else if (e.target.classList.contains('remove-button')) {
            const pathOrderId = e.target.dataset.id;
            const placeId = e.target.dataset.place;
            await removePlace(pathOrderId, document.getElementById(`item-${placeId}`).querySelector('.remove-button'),
                document.getElementById(`pos-input-${placeId}`));
        }
    });

    const addPlace = async (itineraryId, placeId, position, button,input) => {
        const response = await fetch('/itinerary/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ itinerary_id: itineraryId, place_id: placeId, position: position })
        });

        if (response.ok) {
            input.classList.replace('bg-white','bg-red-500');
            button.classList.replace('bg-green-500','bg-red-500');
            button.classList.replace('add-button', 'remove-button');
            button.innerText = '-';
            button.dataset.place = button.dataset.id;
            const pathOrder = await fetch('/itinerary/getPathOrder', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ itinerary_id: itineraryId, place_id: placeId })
            });
            if (pathOrder.ok) {
                button.dataset.id = await pathOrder.text();
            }
        }else{
            alert(await response.text());
        }
    };

    const removePlace = async (pathOrderId, button,input) => {
        const response = await fetch('/itinerary/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({path_order_id: pathOrderId })
        });

        if (response.ok) {
            input.classList.replace('bg-red-500','bg-white');
            button.classList.replace('remove-button', 'add-button');
            button.classList.replace('bg-red-500', 'bg-green-500');
            button.innerText = '+';
            button.dataset.id = button.dataset.place;
        }
    };

});
