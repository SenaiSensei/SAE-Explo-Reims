document.addEventListener("DOMContentLoaded", () => {
    const selectAllCheckbox = document.querySelector("#select-all input[type='checkbox']");
    const checkboxes = document.querySelectorAll("tbody input[type='checkbox']");
    const deleteButton = document.querySelector("#delete-form button[type='submit']");
    const idsInput = document.querySelector("#ids");

    const updateDeleteButtonState = () => {
        const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        deleteButton.disabled = !isAnyChecked;
        deleteButton.classList.toggle("disabled:bg-red-400", !isAnyChecked);
        deleteButton.classList.toggle("bg-red-500", isAnyChecked);
    };

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateDeleteButtonState);
    });

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", (event) => {
            const isChecked = event.target.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateDeleteButtonState();
        });
    }

});

document.getElementById('delete-form').addEventListener('submit', function(event) {
    const selectedIds = [];
    const checkboxes = document.querySelectorAll('input[name="place_ids[]"]:checked');

    checkboxes.forEach(function(checkbox) {
        selectedIds.push(checkbox.value);
    });

    if (selectedIds.length > 0) {
        const confirmation = confirm('Êtes-vous sûr de vouloir supprimer les lieux sélectionnés ?');
        if (!confirmation) {
            event.preventDefault();
        } else {
            const container = document.getElementById('ids-container');
            container.innerHTML = '';

            selectedIds.forEach(function(id) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                container.appendChild(input);
            });
        }
    } else {
        event.preventDefault();
        alert('Veuillez sélectionner au moins un lieu.');
    }
});

