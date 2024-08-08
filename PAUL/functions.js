// Fonction pour basculer l'affichage du formulaire d'ajout
function toggleAddForm() {
    var addFormContainer = document.getElementById("addFormContainer");
    var editFormContainer = document.getElementById("editFormContainer");

    addFormContainer.style.display = addFormContainer.style.display === "none" ? "block" : "none";
    // Assurez-vous de cacher le formulaire de modification si le formulaire d'ajout est affiché
    if (addFormContainer.style.display === "block") {
        editFormContainer.style.display = "none";
    }
}

// Fonction pour basculer l'affichage du formulaire de modification
function toggleEditForm() {
    var editFormContainer = document.getElementById("editFormContainer");
    var addFormContainer = document.getElementById("addFormContainer");

    editFormContainer.style.display = editFormContainer.style.display === "none" ? "block" : "none";
    
    // Cacher le formulaire d'ajout si le formulaire de modification est affiché
    if (editFormContainer.style.display === "block") {
        addFormContainer.style.display = "none";
    }
}

// Fonction pour récupérer les données à modifier
function getEditFormData() {
    var id = document.getElementById('editRowId').value;
    if(id) {
        fetch("get_row_data.php", {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('editIntitule').value = data.Intitule;
            document.getElementById('editObjectifs').value = data.Objectifs;
            document.getElementById('editDatededebut').value = data.DateDeDebut;
            document.getElementById('editDatedefin').value = data.DateDeFin;
            document.getElementById('editAvancement').value = data.Avancement;
        })
        .catch(error => console.error('Error:', error));
    }
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
        location.reload(); // Rechargez la page pour voir les changements
    })
    .catch(error => console.error('Error:', error));
}

// Fonction pour supprimer une ligne par ID
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
                location.reload(); // Rechargez la page pour voir les changements
            })
            .catch(error => console.error('Error:', error));
        }
    } else {
        alert("Veuillez entrer un ID.");
    }
}

// Mettre à jour l'affichage de la valeur d'avancement en pourcentage
function avancementValueDisplay(elementId, value) {
    var avancementPercentage = value + '%';
    document.getElementById(elementId + 'Value').textContent = avancementPercentage;
}

// Fonction pour modifier une ligne
function editRow() {
    var formData = new FormData(document.getElementById("editRowForm"));
    fetch("update_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json()) // Assurez-vous que le serveur renvoie JSON
    .then(data => {
        // Mettez à jour la ligne modifiée dans le tableau
        var rowId = document.getElementById('editRowId').value;
        var editedRow = document.getElementById('row_' + rowId);
        editedRow.innerHTML = data; // Mettez à jour le contenu de la ligne avec les nouvelles données

        toggleEditForm(); // Cacher le formulaire de modification après la mise à jour
        alert("La ligne a été mise à jour avec succès !");

        // Récupérer la valeur de l'avancement et mettre à jour son affichage
        var avancementValue = document.getElementById('editAvancement').value;
        avancementValueDisplay(avancementValue, 'editAvancement');
    })
    .catch(error => console.error('Error:', error));
}

// Fonction pour rafraîchir les données du tableau
function refreshTableData() {
    fetch("fetch_data.php") 
    .then(response => response.text())
    .then(html => {
        const tableContainer = document.getElementById("tableContainer");
        if(tableContainer) {
            tableContainer.innerHTML = html; // Met à jour le contenu du conteneur du tableau
        }
    })
    .catch(error => console.error('Erreur lors de la mise à jour des données du tableau:', error));
}




document.addEventListener('DOMContentLoaded', function() {
    // Sélectionnez toutes les icônes dans la barre latérale
    const icons = document.querySelectorAll('.sidebar .icon');

    // Parcourir chaque icône et ajouter un écouteur d'événements click
    icons.forEach(icon => {
        icon.addEventListener('click', function() {
            // Retirer la classe 'active' de toutes les icônes
            icons.forEach(i => i.classList.remove('active'));

            // Ajouter la classe 'active' à l'icône cliquée
            this.classList.add('active');
        });
    });
});

