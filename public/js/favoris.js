const addFav = async (id,route) => {
    const response = await fetch(route+'favoris/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({id: id})
    });

    if (response.ok) {
        let element = document.getElementById(`item-${id}`);
        let button = element.querySelector('.add-fav-button')
        button.classList.replace('add-fav-button', 'remove-fav-button');
        button.classList.replace('text-black', 'text-red-500');
        button.classList.replace('bx-heart', 'bxs-heart');
        document.getElementById('favoris').appendChild(element);
        document.getElementById('text').classList.add('hidden')
    }
};

const removeFav = async (id,route) => {
    const response = await fetch(route+'favoris/remove', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({id: id})
    });

    if (response.ok) {
        let element = document.getElementById(`item-${id}`);
        let button = element.querySelector('.remove-fav-button')
        button.classList.replace('remove-fav-button', 'add-fav-button');
        button.classList.replace('text-red-500', 'text-black');
        button.classList.replace('bxs-heart', 'bx-heart');
        document.getElementById('pasFavoris').appendChild(element);
        //let children = [].slice.call()
        let nb = document.getElementById('favoris').children.length;
        if (nb < 1) {
            document.getElementById('text').classList.remove('hidden')
        }
    }
};

const gestionFav = async (id,route) => {
    let element = document.getElementById(`item-${id}`);
    let button = element.querySelector('.remove-fav-button')
    if(button){
        await removeFav(id, route);
    }else{
        await addFav(id, route);
    }
}