// Fonction pour filtrer par levier dès que le dropdown change
function filterByLevier() {
    const levierSelect = document.getElementById('levierSelect').value;
    const url = new URL(window.location.href);
    url.searchParams.set('levier', levierSelect);
    window.location.href = url.toString();
}

// Fonction pour trier par date dès que le dropdown change
function sortByDate() {
    const sortDateSelect = document.getElementById('sortDateSelect').value;
    const url = new URL(window.location.href);
    url.searchParams.set('sortDate', sortDateSelect);
    window.location.href = url.toString();
}

// Initialisation de la valeur des dropdowns en fonction des paramètres URL
document.addEventListener('DOMContentLoaded', (event) => {
    const urlParams = new URLSearchParams(window.location.search);
    const levier = urlParams.get('levier');
    const sortDate = urlParams.get('sortDate');
    if (levier) {
        document.getElementById('levierSelect').value = levier;
    }
    if (sortDate) {
        document.getElementById('sortDateSelect').value = sortDate;
    }
});

// Fonction pour supprimer une ligne par son ID
function deleteById() {
    var id = document.getElementById('deleteId').value;
    if (id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer la ligne avec l'ID " + id + " ?")) {
            fetch("delete_row.php", {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + id
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    } else {
        alert("Veuillez entrer un ID.");
    }
}

// Fonction pour afficher la valeur de l'avancement
function avancementValueDisplay(elementId, value) {
    var avancementPercentage = value + '%';
    document.getElementById(elementId + 'Value').textContent = avancementPercentage;
}

// JavaScript pour gérer les actions du formulaire
function toggleAddForm() {
    toggleForm("addFormContainer");
}

function toggleDeleteForm() {
    toggleForm("deleteFormContainer");
}



function toggleForm(formId) {
    var formContainer = document.getElementById(formId);
    var otherForms = ["addFormContainer", "deleteFormContainer", "editFormContainer"];
    formContainer.style.display = formContainer.style.display === "none" ? "block" : "none";
    otherForms.forEach(form => {
        if (form !== formId) {
            document.getElementById(form).style.display = "none";
        }
    });
}

// Fonction pour ajouter une nouvelle ligne
function addRow() {
    var formData = new FormData(document.getElementById("addRowForm"));
    fetch("insert_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}



// Fonction pour rechercher dans le tableau
function searchTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    input = input.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); // Remove accents
    var tableRows = document.querySelectorAll('#tableContainer tr');

    tableRows.forEach(function(row) {
        var columns = row.querySelectorAll('td'); // Ajoutez ici les sélecteurs de vos colonnes
        var found = false;

        columns.forEach(function(column) {
            var text = column.textContent.toLowerCase();
            text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); 
            if (text.includes(input)) {
                found = true;
            }
        });

        if (found) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Fonction pour éditer une ligne
function editRow() {
    var formData = new FormData(document.getElementById("editRowForm"));
    fetch("update_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        var rowId = document.getElementById('editRowId').value;
        var editedRow = document.getElementById('row_' + rowId);
        editedRow.innerHTML = data;

        toggleEditForm();
        alert("La ligne a été mise à jour avec succès !");
        var avancementValue = document.getElementById('editAvancement').value;
        avancementValueDisplay(avancementValue, 'editAvancement');
    })
    .catch(error => console.error('Error:', error));
}

// Fonction pour trier le tableau par date de début
function sortTableByStartDate() {
    const tableContainer = document.getElementById("tableContainer");
    const rows = Array.from(tableContainer.getElementsByTagName("tr")).slice(1);
    rows.sort((a, b) => {
        const dateA = new Date(a.querySelector(".datededebut").innerText);
        const dateB = new Date(b.querySelector(".datededebut").innerText);
        return dateA - dateB;
    });
    rows.forEach(row => tableContainer.appendChild(row));
}

// Fonction pour rafraîchir les données du tableau
function refreshTableData() {
    fetch("fetch_data.php")
    .then(response => response.text())
    .then(html => {
        const tableContainer = document.getElementById("tableContainer");
        if(tableContainer) {
            tableContainer.innerHTML = html;
        }
    })
    .catch(error => console.error('Erreur lors de la mise à jour des données du tableau:', error));
}



// Fonction pour afficher le popup
function openPopupForm() {
    document.getElementById('popupForm').style.display = 'block';
}

// Fonction pour fermer le popup
function closePopupForm() {
    document.getElementById('popupForm').style.display = 'none';
}

// Fonction pour ajouter une nouvelle ligne
function addRow() {
    var formData = new FormData(document.getElementById("addRowForm"));
    fetch("insert_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closePopupForm(); // Ferme le popup après ajout
        refreshTableData(); // Rafraîchit les données du tableau
    })
    .catch(error => console.error('Error:', error));
}


function sortTable(columnIndex, ascending = true) {
    const tableContainer = document.getElementById("tableContainer");
    const rows = Array.from(tableContainer.getElementsByTagName("tr")).slice(1);
    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].innerText;
        const cellB = b.cells[columnIndex].innerText;
        return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });
    rows.forEach(row => tableContainer.appendChild(row));
}

document.querySelectorAll('#tableContainer th').forEach((header, index) => {
    header.addEventListener('click', () => sortTable(index));
});

















// document.addEventListener('DOMContentLoaded', function () {
//     // Affiche le modal d'identification dès que la page est chargée
//     document.getElementById('loginModal').style.display = 'block';
// });

// function closeLoginModal() {
//     document.getElementById('loginModal').style.display = 'none';
// }

// function validateUserId() {
//     const userId = document.getElementById('userId').value;
//     if (userId) {
//         closeLoginModal();
//         // Vous pouvez ajouter des vérifications supplémentaires ici
//     } else {
//         alert('Veuillez entrer un identifiant.');
//     }
// }

