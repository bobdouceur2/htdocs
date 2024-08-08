

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








// JavaScript pour gérer les actions du formulaire
function toggleAddForm() {
    toggleForm("addFormContainer");
}

function toggleDeleteForm() {
    toggleForm("deleteFormContainer");
}

function toggleForm(formId) {
    var formContainer = document.getElementById(formId);
    var otherForms = ["addFormContainer", "deleteFormContainer"];
    formContainer.style.display = formContainer.style.display === "none" ? "block" : "none";
    otherForms.forEach(form => {
        if (form !== formId) {
            document.getElementById(form).style.display = "none";
        }
    });
}






// Fonction pour rechercher dans le tableau
function searchTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    input = input.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    var tableRows = document.querySelectorAll('#tableContainer tr');

    tableRows.forEach(function(row) {
        var columns = row.querySelectorAll('td');
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
            if (tableContainer) {
                tableContainer.innerHTML = html;
            }
        })
        .then(() => {
            // Recharger la page une fois les données du tableau mises à jour
            location.reload();
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










// Fonction pour trier le tableau par une colonne spécifique
function sortTable(columnIndex, ascending = true) {
    // Obtenir l'élément conteneur du tableau
    const tableContainer = document.getElementById("tableContainer");
    // Obtenir toutes les lignes du tableau, en excluant la ligne d'en-tête
    const rows = Array.from(tableContainer.getElementsByTagName("tr")).slice(1);
    // Trier les lignes en fonction du contenu de la colonne spécifiée
    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].innerText;
        const cellB = b.cells[columnIndex].innerText;
        // Comparer les valeurs des cellules en fonction de l'ordre de tri spécifié (ascendant ou descendant)
        return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });
    // Réorganiser les lignes dans le tableau en fonction de l'ordre trié
    rows.forEach(row => tableContainer.appendChild(row));
}




// Ajouter des écouteurs d'événements de clic aux cellules d'en-tête du tableau
document.querySelectorAll('#tableContainer th').forEach((header, index) => {
    header.addEventListener('click', () => sortTable(index));
});





function showAllProjects() {
    // Récupère l'URL actuelle sans les paramètres
    var url = new URL(window.location.href);
    
    // Ajoute ou met à jour le paramètre showAll avec la valeur true
    url.searchParams.set('showAll', 'true');
    
    // Redirige vers l'URL mise à jour
    window.location.href = url.href;
}

function showMyProjects() {
    // Récupère l'URL actuelle sans les paramètres
    var url = new URL(window.location.href);
    
    // Supprime le paramètre showAll s'il existe
    url.searchParams.delete('showAll');
    
    // Redirige vers l'URL sans le paramètre showAll
    window.location.href = url.href;
}



function sortProjects() {
    const sortValue = document.getElementById('sortSelect').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortValue);
    window.location.search = urlParams.toString();
}






// Ajoute un écouteur d'événement qui s'exécute lorsque le DOM a été complètement chargé
document.addEventListener('DOMContentLoaded', (event) => {
    // Crée un objet URLSearchParams pour travailler avec les paramètres de la chaîne de requête de l'URL
    const urlParams = new URLSearchParams(window.location.search);
    
    // Récupère la valeur du paramètre 'sort' de la chaîne de requête
    const sortValue = urlParams.get('sort');
    
    // Vérifie si la valeur du paramètre 'sort' existe
    if (sortValue) {
        // Si la valeur existe, trouve l'élément avec l'ID 'sortSelect' et définit sa valeur sur celle du paramètre 'sort'
        document.getElementById('sortSelect').value = sortValue;
    }
});














document.addEventListener('DOMContentLoaded', (event) => {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const editButton = document.querySelector('.right-sidebar .btn-primary');
    const deleteButton = document.querySelector('.right-sidebar .btn-danger');

    // Fonction pour obtenir les ID des lignes sélectionnées
    function getSelectedRowIds() {
        const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);
        return selectedIds;
    }

    // Fonction pour mettre à jour le texte du bouton de suppression et la visibilité du bouton de modification
    function updateButtons() {
        const selectedIds = getSelectedRowIds();
        
        if (selectedIds.length === 1) {
            editButton.style.display = "block";
            deleteButton.textContent = "Supprimer la ligne";
        } else if (selectedIds.length > 1) {
            editButton.style.display = "none";
            deleteButton.textContent = "Supprimer les lignes";
        } else {
            editButton.style.display = "block";
            deleteButton.textContent = "Supprimer la ligne";
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateButtons();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else if (Array.from(rowCheckboxes).every(checkbox => checkbox.checked)) {
                selectAllCheckbox.checked = true;
            }
            updateButtons();
        });
    });

    // Initial update of the buttons when the page loads
    updateButtons();




    


});














document.addEventListener('DOMContentLoaded', (event) => {
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const id = this.getAttribute('data-id');
                
            }
        });
    });

    // Sélectionner/Désélectionner toutes les checkboxes
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (this.checked) {
                const id = checkbox.getAttribute('data-id');
                
            }
        });
    });
});









document.addEventListener('DOMContentLoaded', (event) => {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const rightSidebar = document.getElementById('rightSidebar');
    const mainContent = document.getElementById('mainContent');

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleRightSidebar();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            }
            toggleRightSidebar();
        });
    });

    function toggleRightSidebar() {
        const selectedCount = document.querySelectorAll('.rowCheckbox:checked').length;
        if (selectedCount > 0) {
            rightSidebar.classList.add('active');
            mainContent.classList.add('shrink');
        } else {
            rightSidebar.classList.remove('active');
            mainContent.classList.remove('shrink');
        }
    }
});






// Fonction pour obtenir les ID des lignes sélectionnées
function getSelectedRowIds() {
    const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);
    
    return selectedIds;
}









// Fonction pour ouvrir le formulaire de modification

function openEditPopupForm() {
    // Obtenir les identifiants des lignes sélectionnées
    const selectedIds = getSelectedRowIds();

    // Afficher les identifiants sélectionnés pour le débogage
    

    // Vérifier si plus d'une ligne est sélectionnée
    if (selectedIds.length !== 1) {
        alert("Vous ne pouvez modifier qu'une seule ligne à la fois.");
        return; // Arrêter l'exécution de la fonction si plus d'une ligne est sélectionnée
    }

    // Obtenir l'ID de la ligne sélectionnée
    const id = selectedIds[0];

    // Effectuer une requête fetch vers le script PHP pour obtenir les données de la ligne spécifique par son identifiant (id)
    fetch(`get_row.php?id=${id}`)
        // Attendre la réponse de la requête et la convertir en JSON
        .then(response => response.json())
        // Une fois les données converties en JSON, les utiliser pour remplir le formulaire
        .then(data => {
            

            // Vérifier s'il y a une erreur dans les données reçues
            if (data.error) {
                // Si une erreur est présente, afficher une alerte avec le message d'erreur
                alert(data.error);
            } else {
                // Sinon, remplir les champs du formulaire avec les données reçues
                document.getElementById('originalId').value = data.ID;
                document.getElementById('editId').value = data.ID;
                document.getElementById('editIntitule').value = data.Intitule;
                document.getElementById('editObjectifs').value = data.Objectifs;
                document.getElementById('editDatededebut').value = data.DateDeDebut;
                document.getElementById('editDatedefin').value = data.DateDeFin;
                document.getElementById('editAvancement').value = data.Avancement;
                document.getElementById('editAvancementValue').textContent = data.Avancement + '%';
                document.getElementById('editParticipants').value = data.Participants;
                document.getElementById('editLevier').value = data.Levier;
                document.getElementById('editPopupForm').style.display = 'block';
            }
        })
        // Gérer les erreurs éventuelles de la requête fetch et les afficher dans la console
        .catch(error => console.error('Error:', error));
}











// Fonction pour fermer le formulaire de modification
function closeEditPopupForm() {
    document.getElementById('editPopupForm').style.display = 'none';
}










// Fonction pour sauvegarder les modifications
function saveEditedRow() {
    // Récupérer le formulaire de modification par son identifiant 'editRowForm'
    const formData = new FormData(document.getElementById('editRowForm'));
    
    // Envoyer une requête fetch pour sauvegarder les modifications sur le serveur
    fetch('edit_row.php', {
        // Utiliser la méthode POST pour envoyer les données
        method: 'POST',
        // Inclure le formulaire contenant les données modifiées
        body: formData,
    })
    // Traiter la réponse du serveur après l'envoi du formulaire
    .then(response => response.text()) // Convertir la réponse en texte
    .then(data => {
        // Afficher un message de confirmation avec les données retournées par le serveur
       
        
        // Fermer le formulaire popup de modification
        closeEditPopupForm();
        
        // Recharger les données du tableau pour refléter les modifications
        refreshTableData(); // Recharge le tableau après la mise à jour
    })
    // Gérer les erreurs éventuelles de la requête fetch
    .catch(error => console.error('Error:', error)); // Afficher les erreurs dans la console
}









// Mise à jour de la valeur de l'avancement
document.getElementById('editAvancement').addEventListener('input', function() {
    document.getElementById('editAvancementValue').textContent = this.value + '%';
});






// Fonction pour supprimer les lignes sélectionnées
function deleteSelectedRow() {
    // Obtenir les identifiants des lignes sélectionnées
    const selectedIds = getSelectedRowIds();

    // Vérifier si des lignes sont sélectionnées
    if (selectedIds.length > 0) {
        const confirmMessage = selectedIds.length === 1 ?
            "Êtes-vous sûr de vouloir supprimer cette ligne ?" :
            "Êtes-vous sûr de vouloir supprimer ces lignes ?";
        
        // Demander confirmation avant la suppression
        if (confirm(confirmMessage)) {
            // Effectuer une requête fetch vers le script PHP pour supprimer les lignes
            fetch('delete_row.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.text())
            .then(data => {
                
                refreshTableData(); // Recharger le tableau après la suppression
            })
            .catch(error => console.error('Error:', error));
        }
    } else {
        alert("Veuillez sélectionner au moins une ligne à supprimer.");
    }
}



// Ajout de l'événement pour la touche Entrée sur le champ de recherche
document.getElementById('searchInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault(); // Empêche le comportement par défaut du formulaire
        searchTable();
    }
});


// Sélectionnez le slider et l'élément span pour la valeur d'avancement
const slider = document.getElementById('avancement');
const avancementValue = document.getElementById('avancementValue');


// Écoutez les changements de valeur du slider
slider.addEventListener('input', function() {
    // Mettez à jour le contenu du span avec la valeur du slider
    avancementValue.textContent = slider.value + '%';
});

document.getElementById('avancement').addEventListener('input', function() {
    document.getElementById('avancementValue').textContent = this.value + '%';
});


function addRow() {
    console.log("addRow() called"); // Log pour vérifier si la fonction est bien appelée
    
    var form = document.getElementById("addRowForm");
    var formData = new FormData(form);

    var missingParams = [];

    // Vérifier chaque champ requis
    if (!formData.get('id')) missingParams.push('ID');
    if (!formData.get('intitule')) missingParams.push('Intitulé');
    if (!formData.get('objectifs')) missingParams.push('Objectifs');
    if (!formData.get('datededebut')) missingParams.push('Date de début');
    if (!formData.get('datedefin')) missingParams.push('Date de fin');
    if (!formData.get('avancement')) missingParams.push('Avancement');
    if (!formData.get('participants')) missingParams.push('Participants');
    if (!formData.get('levier')) missingParams.push('Levier');
    if (!formData.get('localisation')) missingParams.push('Localisation');

    if (missingParams.length > 0) {
        alert("Les paramètres suivants sont manquants ou vides : " + missingParams.join(', '));
        console.log("Missing parameters: ", missingParams); // Log des paramètres manquants
        return;
    }

    // Log pour vérifier les données du formulaire avant l'envoi
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ': ' + pair[1]);
    }

    fetch("insert_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log("Response received: ", data); // Log de la réponse du serveur

        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        if (data.includes("succès")) {
            messageDiv.style.color = 'green';
            closePopupForm();
            refreshTableData();
        } else {
            messageDiv.style.color = 'red';
        }
        messageDiv.innerText = data;
    })
    .catch(error => {
        console.log("Error: ", error); // Log de l'erreur

        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.style.color = 'red';
        messageDiv.innerText = 'Erreur : ' + error;
    });
}





function openVisualizationPopUp() {
    document.getElementById('visualizationPopupForm').style.display = 'block';
}

function closeVisualizationPopupForm() {
    document.getElementById('visualizationPopupForm').style.display = 'none';
}



function submitVisualizationForm() {
    let id = document.getElementById('visualizationId').value;
    if (id) {
        window.open(`visualization.php?id=${id}`, '_blank');
    }
}


function logoutUser() {
    // Afficher une fenêtre de confirmation
    if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        // Si l'utilisateur confirme, rediriger vers logout.php
        window.location.href = 'logout.php';
    }
    // Sinon, ne rien faire
}



function openSettingsPopup() {
    document.getElementById("settingsPopupForm").style.display = "block";
}

function closeSettingsPopup() {
    document.getElementById("settingsPopupForm").style.display = "none";
}

function goToAdminMode() {
    window.location.href = 'admin_mode.php'; // Change la destination selon tes besoins
}


function toggleSearch() {
    const searchContainer = document.querySelector('.search-container');
    const searchInput = document.getElementById('searchInput');

    if (searchContainer.classList.contains('active')) {
        searchContainer.classList.remove('active');
        searchInput.value = ''; // Réinitialise le champ de recherche lorsque replié
    } else {
        searchContainer.classList.add('active');
        searchInput.focus(); // Focalise automatiquement sur la barre de recherche
    }
}

// Fermer la barre de recherche si on clique en dehors
document.addEventListener('click', function(event) {
    const searchContainer = document.querySelector('.search-container');
    if (!searchContainer.contains(event.target) && searchContainer.classList.contains('active')) {
        searchContainer.classList.remove('active');
        document.getElementById('searchInput').value = ''; // Réinitialise le champ de recherche lorsque replié
    }
});






