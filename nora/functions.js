function deleteById() {
    const id = document.getElementById('deleteId').value;
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
    const formContainer = document.getElementById(formId);
    const otherForms = ["addFormContainer", "deleteFormContainer"];
    formContainer.style.display = formContainer.style.display === "none" ? "block" : "none";
    otherForms.forEach(form => {
        if (form !== formId) {
            document.getElementById(form).style.display = "none";
        }
    });
}

document.getElementById('searchInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        console.log("Recherche déclenchée avec la valeur :", e.target.value);
        searchTable();
    }
});

function searchTable() {
    console.log("Fonction searchTable() appelée.");
    let input = document.getElementById('searchInput').value.toLowerCase();
    input = input.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    console.log("Valeur de recherche normalisée :", input);
    const tableRows = document.querySelectorAll('#tableContainer tr');

    tableRows.forEach(row => {
        const columns = row.querySelectorAll('td');
        let found = false;

        columns.forEach(column => {
            let text = column.textContent.toLowerCase();
            text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            if (text.includes(input)) {
                found = true;
            }
        });

        row.style.display = found ? '' : 'none';
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
            location.reload();
        })
        .catch(error => console.error('Erreur lors de la mise à jour des données du tableau:', error));
}

// Fonction pour afficher et fermer les popups
function openPopupForm() {
    document.getElementById('popupForm').style.display = 'block';
}

function closePopupForm() {
    document.getElementById('popupForm').style.display = 'none';
}

function openVisualizationPopUp() {
    document.getElementById('visualizationPopupForm').style.display = 'block';
}

function closeVisualizationPopupForm() {
    document.getElementById('visualizationPopupForm').style.display = 'none';
}





// Fonction pour afficher tous les projets
function showAllProjects() {
    console.log("showAllProjects() appelée");  // Log pour indiquer que la fonction est appelée
    
    const url = new URL(window.location.href);
    console.log("URL actuelle:", url.href);  // Log pour afficher l'URL actuelle avant modification
    
    url.searchParams.set('showAll', 'true');  // Ajout du paramètre 'showAll'
    console.log("'showAll' ajouté à l'URL:", url.href);  // Log pour vérifier que le paramètre a été ajouté
    
    window.location.href = url.href;  // Redirection vers la nouvelle URL
    console.log("Redirection vers:", url.href);  // Log pour indiquer l'URL finale vers laquelle la redirection va se faire
}


// Fonction pour afficher uniquement les projets de l'utilisateur
function showMyProjects() {
    console.log("showMyProjects() appelée");  // Log pour indiquer que la fonction est appelée
    
    const url = new URL(window.location.href);
    console.log("URL actuelle:", url.href);  // Log pour afficher l'URL actuelle avant modification
    
    url.searchParams.delete('showAll');  // Suppression du paramètre 'showAll'
    console.log("'showAll' supprimé de l'URL:", url.href);  // Log pour vérifier que le paramètre a été supprimé
    
    window.location.href = url.href;  // Redirection vers la nouvelle URL
    console.log("Redirection vers:", url.href);  // Log pour indiquer l'URL finale vers laquelle la redirection va se faire
}


function sortProjects() {
    const sortValue = document.getElementById('sortSelect').value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortValue);
    window.location.search = urlParams.toString();
}

// Gestion de la sélection des lignes et boutons associés
document.addEventListener('DOMContentLoaded', () => {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const editButton = document.querySelector('.right-sidebar .btn-primary');
    const deleteButton = document.querySelector('.right-sidebar .btn-danger');

    function getSelectedRowIds() {
        const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
        return Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);
    }

    function updateButtons() {
        const selectedIds = getSelectedRowIds();
        if (selectedIds.length === 1) {
            editButton.style.display = "block";
            deleteButton.style.display = "block";
            deleteButton.textContent = "Supprimer la ligne";
        } else if (selectedIds.length > 1) {
            editButton.style.display = "none";
            deleteButton.style.display = "block";
            deleteButton.textContent = "Supprimer les projets";
        } else {
            editButton.style.display = "none";
            deleteButton.style.display = "none";
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

    updateButtons(); // Initial update of the buttons when the page loads
});

document.addEventListener('DOMContentLoaded', () => {
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
        const buttons = rightSidebar.querySelectorAll('button');
        if (selectedCount > 0) {
            rightSidebar.classList.add('active');
            mainContent.classList.add('shrink');
            rightSidebar.style.display = 'flex';
            buttons.forEach(button => button.disabled = false);
        } else {
            rightSidebar.classList.remove('active');
            mainContent.classList.remove('shrink');
            rightSidebar.style.display = 'none';
            buttons.forEach(button => button.disabled = true);
        }
    }

    toggleRightSidebar(); // Initial state
});

// Fonction pour obtenir les ID des lignes sélectionnées
function getSelectedRowIds() {
    const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
    return Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);
}

function openEditPopupForm() {
    console.log("openEditPopupForm function called");  // Log de débogage

    const selectedIds = getSelectedRowIds();
    if (selectedIds.length !== 1) {
        alert("Vous ne pouvez modifier qu'une seule ligne à la fois.");
        return;
    }

    const id = selectedIds[0];
    console.log("ID sélectionné:", id);  // Log de l'ID sélectionné

    fetch(`get_row.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            console.log("Données reçues de get_row.php:", data);  // Log des données reçues

            if (data.error) {
                alert(data.error);
            } else {
                // Remplissage des champs du formulaire avec les données reçues
                
                document.getElementById('editId').value = data.ID;
                document.getElementById('editIntitule').value = data.Intitule;
                document.getElementById('editDescriptionProbleme').value = data.DescriptionProbleme;
                document.getElementById('editObjectifsOperationnels').value = data.ObjectifsOperationnels;
                document.getElementById('editDatededebut').value = data.DateDeDebut;
                document.getElementById('editDatedefin').value = data.DateDeFin;
                document.getElementById('editAvancement').value = data.Avancement;
                document.getElementById('editAvancementValue').textContent = data.Avancement + '%';
                document.getElementById('editEquipe').value = data.Equipe;
                document.getElementById('editObjectifs').value = data.Objectifs;
                document.getElementById('editParticipants').value = data.Participants;
                document.getElementById('editLevier').value = data.Levier;
                document.getElementById('editLocalisation').value = data.Localisation;
                document.getElementById('editPerimetre').value = data.Perimetre;
                document.getElementById('editPlanning').value = data.Planning;

                // Effacer les anciennes entrées de dates jalon
                const jalonContainer = document.getElementById('editDatesJalonContainer');
                jalonContainer.innerHTML = `<label><b>Dates Jalon</b></label>`;  // Réinitialiser le conteneur

                // Charger les dates jalon existantes
                if (data.dates_jalon) {
                    try {
                        const jalonData = JSON.parse(data.dates_jalon);
                        jalonData.forEach(jalon => {
                            const newEntry = document.createElement('div');
                            newEntry.classList.add('dates-jalon-entry');
                            newEntry.innerHTML = `
                                <input type="date" name="edit_jalon_dates[]" value="${jalon.date}">
                                <input type="text" name="edit_jalon_texts[]" value="${jalon.text}">
                                <button type="button" onclick="removeDateJalonEntry(this)">Supprimer</button>
                            `;
                            jalonContainer.appendChild(newEntry);
                        });
                    } catch (e) {
                        console.error('Erreur de parsing JSON pour dates_jalon:', e);
                    }
                }

                document.getElementById('editPopupForm').style.display = 'block';
            }
        })
        .catch(error => console.error('Error:', error));
}



// Fonction pour fermer le formulaire de modification
function closeEditPopupForm() {
    document.getElementById('editPopupForm').style.display = 'none';
}


function saveEditedRow() {
    const formData = new FormData(document.getElementById('editRowForm'));
    console.log("saveEditRow bien lancée");

    // Récupérer les dates jalons et leurs descriptions
    const jalonDates = document.querySelectorAll('input[name="edit_jalon_dates[]"]');
    const jalonTexts = document.querySelectorAll('input[name="edit_jalon_texts[]"]');
    const datesJalonArray = [];

    // Parcourir les dates jalons et leurs descriptions pour créer un tableau d'objets
    jalonDates.forEach((dateInput, index) => {
        const textInput = jalonTexts[index];
        if (dateInput.value && textInput.value) {
            datesJalonArray.push({ date: dateInput.value, text: textInput.value });
        }
    });

    // Affichage des dates jalons avant d'ajouter au FormData
    console.log("Dates jalons avant conversion en JSON:", datesJalonArray);

    // Ajouter les dates jalons sous forme de chaîne JSON au FormData
    if (datesJalonArray.length > 0) {
        const datesJalonJSON = JSON.stringify(datesJalonArray);
        formData.append('dates_jalon', datesJalonJSON);
        console.log("Dates jalons sous forme JSON:", datesJalonJSON);
    } else {
        formData.append('dates_jalon', '');
        console.log("Aucune date jalon trouvée, envoi d'une chaîne vide.");
    }

    // Affichage du contenu de FormData
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ': ' + pair[1]); 
    }

    fetch('edit_row.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur de réseau lors de la requête.');
        }
        return response.text();
    })
    .then(data => {
        console.log("Réponse du serveur:", data);  // Log de la réponse du serveur
        if (data.includes("Erreur")) {
            console.error('Erreur lors de la mise à jour :', data);
        } else {
            closeEditPopupForm();
            refreshTableData();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}






// Mise à jour de la valeur de l'avancement
document.getElementById('editAvancement').addEventListener('input', function() {
    document.getElementById('editAvancementValue').textContent = this.value + '%';
});

// Fonction pour supprimer les lignes sélectionnées
function deleteSelectedRow() {
    const selectedIds = getSelectedRowIds();
    if (selectedIds.length > 0) {
        const confirmMessage = selectedIds.length === 1 ?
            "Êtes-vous sûr de vouloir supprimer cette ligne ?" :
            "Êtes-vous sûr de vouloir supprimer ces lignes ?";
        if (confirm(confirmMessage)) {
            fetch('delete_row.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.text())
            .then(data => {
                refreshTableData();
            })
            .catch(error => console.error('Error:', error));
        }
    } else {
        alert("Veuillez sélectionner au moins une ligne à supprimer.");
    }
}

document.getElementById('searchInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchTable();
    }
});

const slider = document.getElementById('avancement');
const avancementValue = document.getElementById('avancementValue');

slider.addEventListener('input', function() {
    avancementValue.textContent = slider.value + '%';
});

function addRow() {
    console.log("addRow() called");
    const form = document.getElementById("addRowForm");
    const formData = new FormData(form);

    const missingParams = [];
    if (!formData.get('id')) missingParams.push('ID');
    if (!formData.get('intitule')) missingParams.push('Intitulé');
    if (!formData.get('datededebut')) missingParams.push('Date de début');
    if (!formData.get('datedefin')) missingParams.push('Date de fin');
    if (!formData.get('avancement')) missingParams.push('Avancement');

    const jalonDates = formData.getAll('jalon_dates[]');
    const jalonTexts = formData.getAll('jalon_texts[]');
    const datesJalonArray = [];

    jalonDates.forEach((date, index) => {
        if (date && jalonTexts[index]) {
            datesJalonArray.push({ date: date, text: jalonTexts[index] });
        }
    });

    if (datesJalonArray.length > 0) {
        formData.append('dates_jalon', JSON.stringify(datesJalonArray));
    } else {
        formData.append('dates_jalon', '');
    }

    if (missingParams.length > 0) {
        alert("Les paramètres suivants sont manquants ou vides : " + missingParams.join(', '));
        console.log("Missing parameters: ", missingParams);
        return;
    }

    fetch("insert_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log("Response received: ", data);
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
        console.log("Error: ", error);
        const messageDiv = document.getElementById('message');
        messageDiv.style.display = 'block';
        messageDiv.style.color = 'red';
        messageDiv.innerText = 'Erreur : ' + error;
    });
}






function submitVisualizationForm() {
    const id = document.getElementById('visualizationId').value;
    if (id) {
        window.open(`visualization.php?id=${id}`, '_blank');
    }
}

function logoutUser() {
    if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        window.location.href = 'logout.php';
    }
}

function openSettingsPopup() {
    document.getElementById("settingsPopupForm").style.display = "block";
}

function closeSettingsPopup() {
    document.getElementById("settingsPopupForm").style.display = "none";
}

function goToAdminMode() {
    window.location.href = 'admin_login.php';
}

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.value = '';
    searchInput.focus();
}

document.addEventListener('click', function(event) {
    const searchContainer = document.querySelector('.search-container');
    if (!searchContainer.contains(event.target) && searchContainer.classList.contains('active')) {
        searchContainer.classList.remove('active');
        document.getElementById('searchInput').value = '';
    }
});

function openUploadPopup() {
    document.getElementById('uploadPopupForm').style.display = 'block';
}

function closeUploadPopup() {
    document.getElementById('uploadPopupForm').style.display = 'none';
}

function toggleSidebarExpansion() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar.getAttribute('data-expanded') === 'true') {
        sidebar.style.width = '70px';
        sidebar.setAttribute('data-expanded', 'false');
        sidebar.classList.remove('expanded');
    } else {
        sidebar.style.width = '250px';
        sidebar.setAttribute('data-expanded', 'true');
        sidebar.classList.add('expanded');
    }
}
