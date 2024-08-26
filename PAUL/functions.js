

// Démarrer la session et vérifier si l'utilisateur est connecté
function redirectIfNotLoggedIn() {
    session_start();
    if (!isset($_SESSION['userId'])) {
        header('Location: index.php');
        exit();
    }
}

// Obtenir l'année à partir de l'URL ou utiliser l'année courante
function getYear() {
    return isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
}

// Se connecter à la base de données
function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "your_database_name";

    // Créer une connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Générer le diagramme de Gantt
function generateGanttChart($year, $conn) {
    // Ici, vous insérez la logique spécifique de génération de votre diagramme de Gantt.
    // Cet exemple simple est là pour démonstration.
    echo "<div>Gantt chart for the year $year</div>";
}

// Fermer la connexion à la base de données
function closeDatabaseConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}


    // JavaScript pour gérer les actions du formulaire
    function toggleAddForm() {
        var addFormContainer = document.getElementById("addFormContainer");
        var editFormContainer = document.getElementById("editFormContainer");

        addFormContainer.style.display = addFormContainer.style.display === "none" ? "block" : "none";
        if (addFormContainer.style.display === "block") {
            editFormContainer.style.display = "none";
        }
    }

    function toggleEditForm() {
        var editFormContainer = document.getElementById("editFormContainer");
        var addFormContainer = document.getElementById("addFormContainer");

        editFormContainer.style.display = editFormContainer.style.display === "none" ? "block" : "none";
        if (editFormContainer.style.display === "block") {
            addFormContainer.style.display = "none";
        }
    }

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

    function avancementValueDisplay(elementId, value) {
        var avancementPercentage = value + '%';
        document.getElementById(elementId + 'Value').textContent = avancementPercentage;
    }

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

    document.querySelector('.settings-icon').addEventListener('click', function() {
        location.href = 'login.php';
    });
   

