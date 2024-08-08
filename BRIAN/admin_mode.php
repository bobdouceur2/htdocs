<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Projets</title>
    <link rel="stylesheet" href="style.css"> <!--chemin vers styles.css correct -->
</head>

<body>
    <header>
        <img src="logo.png" alt="Logo Safran" id="logoSafran" />
         <!-- Filtre par levier -->
         <div id="filterFormContainer">
            <form id="filterForm">
        
                <select id="levierSelect" name="levier">
                    <option value="">Filtrer par levier 4.0</option>
                    <option value="MFG L1 : PLM">MFG L1 : PLM</option>
                    <option value="MFG L2 : ERP">MFG L2 : ERP</option>
                    <option value="MFG L3 : MES & D. Scheduling">MFG L3 : MES & D. Scheduling</option>
                    <option value="MFG L4 : MCS & 5G & WIFI 6E">MFG L4 : MCS & 5G & WIFI 6E</option>
                    <option value="MFG L5 : Digital Twins">MFG L5 : Digital Twins</option>
                    <option value="MFG L6 : Lean 4.0 Methods">MFG L6 : Lean 4.0 Methods</option>
                    <option value="MFG L7 : Lean 4.0 Tools">MFG L7 : Lean 4.0 Tools</option>
                    <option value="MFG L8 : Lean 4.0 Teams">MFG L8 : Lean 4.0 Teams</option>
                    <option value="MFG L9 : IT-RB & Cyber">MFG L9 : IT-RB & Cyber</option>
                    <option value="MFG L10 : Closed Door Equipment">MFG L10 : Closed Door Equipment</option>
                    <option value="MFG L11 : Manufacturing Data analysis">MFG L11 : Manufacturing Data analysis</option>
                    <option value="MFG L12 : Cobots & Robots">MFG L12 : Cobots & Robots</option>
                    <option value="MFG L13 : Augmented Reality">MFG L13 : Augmented Reality</option>
                    <option value="MFG L14 : Digital Inspection & Automated Decision">MFG L14 : Digital Inspection & Automated Decision</option>
                    <option value="MFG L15 : Marking & Traceability">MFG L15 : Marking & Traceability</option>
                    <option value="MFG L16 : IIOT">MFG L16 : IIOT</option>
                    <option value="MFG L17 : CMMS & Remote maintenance">MFG L17 : CMMS & Remote maintenance</option>
                    <option value="MFG L18 : FMS & BIM">MFG L18 : FMS & BIM</option>
                    <option value="MFG L18 : Supply Chain 4.0">MFG L18 : Supply Chain 4.0</option>
                    <option value="ENG LX : Project Management">ENG LX : Project Management</option>
                    <option value="ENG LX : Agile">ENG LX : Agile</option>
                    <option value="ENG LX : Development Cockpit & Design to Value">ENG LX : Development Cockpit & Design to Value</option>
                    <option value="ENG LX : Virtual & Augmented Reality">ENG LX : Virtual & Augmented Reality</option>
                    <option value="ENG LX : Remote collaboration">ENG LX : Remote collaboration</option>
                    <option value="ENG LX : MBSE">ENG LX : MBSE</option>
                    <option value="ENG LX : Software">ENG LX : Software</option>
                    <option value="ENG LX : Model Based Definition">ENG LX : Model Based Definition</option>
                    <option value="ENG LX : Industrialisation tools">ENG LX : Industrialisation tools</option>
                    <option value="ENG LX : Services authoring tools">ENG LX : Services authoring tools</option>
                    <option value="ENG LX : Data analytics">ENG LX : Data analytics</option>
                    <option value="ENG LX : Simulation Tools">ENG LX : Simulation Tools</option>
                    <option value="ENG LX : PLM - As-required">ENG LX : PLM - As-required</option>
                    <option value="ENG LX : PLM - As-designed">ENG LX : PLM - As-designed</option>
                    <option value="ENG LX : PLM - As-planned / As-built">ENG LX : PLM - As-planned / As-built</option>
                    <option value="ENG LX : PLM - As-to be maintained / As-maintained">ENG LX : PLM - As-to be maintained / As-maintained</option>
                    <option value="ENG LX : Simulations management">ENG LX : Simulations management</option>
                    <option value="ENG LX : Tests Management">ENG LX : Tests Management</option>
                    <option value="ENG LX : Materials and Processis Database a LIMS">ENG LX : Materials and Processis Database a LIMS</option>
                    <option value="EEX LX : New Attitudes">EEX LX : New Attitudes</option>
                    <option value="EEX LX : Leadership Model Evolution">EEX LX : Leadership Model Evolution</option>
                    <option value="EEX LX : HR processes adaptation">EEX LX : HR processes adaptation</option>
                    <option value="EEX LX : Well being @e-work">EEX LX : Well being @e-work</option>
                    <option value="EEX LX : New Internal rules">EEX LX : New Internal rules</option>
                    <option value="EEX LX : Digital Competencies">EEX LX : Digital Competencies</option>
                    <option value="EEX LX : Digital Learning">EEX LX : Digital Learning</option>
                    <option value="EEX LX : Digital resources management">EEX LX : Digital resources management</option>
                    <option value="EEX LX : Learning Material">EEX LX : Learning Material</option>
                    <option value="EEX LX : Collaborations & innovation studio">EEX LX : Collaborations & innovation studio</option>
                    <option value="EEX LX : Team work 4.0">EEX LX : Team work 4.0</option>
                    <option value="EEX LX : Professional social network 4.0">EEX LX : Professional social network 4.0</option>
                    <option value="EEX LX : LiveChat / Webinars">EEX LX : LiveChat / Webinars</option>
                    <option value="EEX LX : Simplified UX">EEX LX : Simplified UX</option>
                    <option value="EEX LX : Easy Access">EEX LX : Easy Access</option>
                    <option value="EEX LX : Communication & Services Portal">EEX LX : Communication & Services Portal</option>
                    <option value="EEX LX : Ideation & Engagement Tools">EEX LX : Ideation & Engagement Tools</option>
                    <option value="SSS LX : Dispatch Reliability Advanced services">SSS LX : Dispatch Reliability Advanced services</option>
                    <option value="SSS LX : Remote inspection / remote assistance">SSS LX : Remote inspection / remote assistance</option>
                    <option value="SSS LX : 3D AR / VR Training">SSS LX : 3D AR / VR Training</option>
                    <option value="SSS LX : Advanced Contracts control system">SSS LX : Advanced Contracts control system</option>
                    <option value="SSS LX : Spare parts advanced SCM">SSS LX : Spare parts advanced SCM</option>
                    <option value="SSS LX : Spare parts data exchange">SSS LX : Spare parts data exchange</option>
                    <option value="SSS LX : Smart MRO">SSS LX : Smart MRO</option>
                    <option value="SSS LX : Sales e-tools">SSS LX : Sales e-tools</option>
                    <option value="SSS LX : RPA">SSS LX : RPA</option>
                    <option value="SSS LX : CRM">SSS LX : CRM</option>
                    <option value="SSS LX : Customer Portal">SSS LX : Customer Portal</option>
                    <option value="SSS LX : PLM">SSS LX : PLM</option>
                    <option value="SSS LX : ERP+">SSS LX : ERP+</option>
                    <option value="SSS LX : Technical Documentation">SSS LX : Technical Documentation</option>
                    <option value="Autre">Autre</option>
                    <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                    <option value="KPI Transverse">KPI Transverse</option>
                    <option value="Roadmap">Roadmap</option>

                </select>
                <button type="submit">Filtrer</button>
            </form>
    </div>
    </header>
    
    <div class="admin-button-container">
        <button onclick="location.href='table1.php'" class="button">Mode Utilisateur</button>
    </div>

    
   

<?php
// Inclusion du fichier de connexion à la base de données.
require_once 'db_connection.php';
?>

<div class="main-content">
    <!-- Conteneur pour le tableau avec barre de défilement -->
    <div class="table-scroll">
        <div id="tableContainerAdmin">
            <?php require_once 'fetch_data_admin.php'; ?>
        </div>
    </div>
</div>



<div class="buttonsContainer">
<!-- Bouton pour afficher le formulaire d'ajout de ligne -->
<button onclick="toggleAddForm()" class="button">Ajouter une ligne</button>

<!-- Bouton pour afficher le formulaire de suppression de ligne -->
<button onclick="toggleDeleteForm()" class="button">Supprimer une ligne</button>

<!-- Bouton pour afficher le formulaire de modification de ligne -->
<button onclick="toggleEditForm()" class="button">Modifier une ligne</button>
</div>



    <!-- Formulaire d'ajout de ligne (caché par défaut) -->
    <div id="addFormContainer" class="formContainer" style="display: none;">
        <h2>Ajouter une nouvelle ligne :</h2>
        <form id="addRowForm">
            <!-- Champs de formulaire -->
            <input type="text" name="intitule" placeholder="Intitulé" required /><br>
            <input type="text" name="objectifs" placeholder="Objectifs" required /><br>
            <input type="date" name="datededebut" placeholder="Date de début" required /><br>
            <input type="date" name="datedefin" placeholder="Date de fin" required /><br>
            <!-- Champ pour l'Avancement -->
            
            <label for="Avancement">Avancement :</label>
            <input type="range" id="addAvancement" name="avancement" min="0" max="100" value="0" oninput="avancementValueDisplay('addAvancement', this.value)" style="width: 100%;">
            <span id="addAvancementValue">0%</span><br>

            <!-- Sélecteur pour le levier -->
            
            <select name="levier" id="levier" required>
                <option value="">Sélectionnez un levier</option>
                <option value="">Tous</option>
                    <option value="MFG L1 : PLM">MFG L1 : PLM</option>
                    <option value="MFG L2 : ERP">MFG L2 : ERP</option>
                    <option value="MFG L3 : MES & D. Scheduling">MFG L3 : MES & D. Scheduling</option>
                    <option value="MFG L4 : MCS & 5G & WIFI 6E">MFG L4 : MCS & 5G & WIFI 6E</option>
                    <option value="MFG L5 : Digital Twins">MFG L5 : Digital Twins</option>
                    <option value="MFG L6 : Lean 4.0 Methods">MFG L6 : Lean 4.0 Methods</option>
                    <option value="MFG L7 : Lean 4.0 Tools">MFG L7 : Lean 4.0 Tools</option>
                    <option value="MFG L8 : Lean 4.0 Teams">MFG L8 : Lean 4.0 Teams</option>
                    <option value="MFG L9 : IT-RB & Cyber">MFG L9 : IT-RB & Cyber</option>
                    <option value="MFG L10 : Closed Door Equipment">MFG L10 : Closed Door Equipment</option>
                    <option value="MFG L11 : Manufacturing Data analysis">MFG L11 : Manufacturing Data analysis</option>
                    <option value="MFG L12 : Cobots & Robots">MFG L12 : Cobots & Robots</option>
                    <option value="MFG L13 : Augmented Reality">MFG L13 : Augmented Reality</option>
                    <option value="MFG L14 : Digital Inspection & Automated Decision">MFG L14 : Digital Inspection & Automated Decision</option>
                    <option value="MFG L15 : Marking & Traceability">MFG L15 : Marking & Traceability</option>
                    <option value="MFG L16 : IIOT">MFG L16 : IIOT</option>
                    <option value="MFG L17 : CMMS & Remote maintenance">MFG L17 : CMMS & Remote maintenance</option>
                    <option value="MFG L18 : FMS & BIM">MFG L18 : FMS & BIM</option>
                    <option value="MFG L18 : Supply Chain 4.0">MFG L18 : Supply Chain 4.0</option>
                    <option value="ENG LX : Project Management">ENG LX : Project Management</option>
                    <option value="ENG LX : Agile">ENG LX : Agile</option>
                    <option value="ENG LX : Development Cockpit & Design to Value">ENG LX : Development Cockpit & Design to Value</option>
                    <option value="ENG LX : Virtual & Augmented Reality">ENG LX : Virtual & Augmented Reality</option>
                    <option value="ENG LX : Remote collaboration">ENG LX : Remote collaboration</option>
                    <option value="ENG LX : MBSE">ENG LX : MBSE</option>
                    <option value="ENG LX : Software">ENG LX : Software</option>
                    <option value="ENG LX : Model Based Definition">ENG LX : Model Based Definition</option>
                    <option value="ENG LX : Industrialisation tools">ENG LX : Industrialisation tools</option>
                    <option value="ENG LX : Services authoring tools">ENG LX : Services authoring tools</option>
                    <option value="ENG LX : Data analytics">ENG LX : Data analytics</option>
                    <option value="ENG LX : Simulation Tools">ENG LX : Simulation Tools</option>
                    <option value="ENG LX : PLM - As-required">ENG LX : PLM - As-required</option>
                    <option value="ENG LX : PLM - As-designed">ENG LX : PLM - As-designed</option>
                    <option value="ENG LX : PLM - As-planned / As-built">ENG LX : PLM - As-planned / As-built</option>
                    <option value="ENG LX : PLM - As-to be maintained / As-maintained">ENG LX : PLM - As-to be maintained / As-maintained</option>
                    <option value="ENG LX : Simulations management">ENG LX : Simulations management</option>
                    <option value="ENG LX : Tests Management">ENG LX : Tests Management</option>
                    <option value="ENG LX : Materials and Processis Database a LIMS">ENG LX : Materials and Processis Database a LIMS</option>
                    <option value="EEX LX : New Attitudes">EEX LX : New Attitudes</option>
                    <option value="EEX LX : Leadership Model Evolution">EEX LX : Leadership Model Evolution</option>
                    <option value="EEX LX : HR processes adaptation">EEX LX : HR processes adaptation</option>
                    <option value="EEX LX : Well being @e-work">EEX LX : Well being @e-work</option>
                    <option value="EEX LX : New Internal rules">EEX LX : New Internal rules</option>
                    <option value="EEX LX : Digital Competencies">EEX LX : Digital Competencies</option>
                    <option value="EEX LX : Digital Learning">EEX LX : Digital Learning</option>
                    <option value="EEX LX : Digital resources management">EEX LX : Digital resources management</option>
                    <option value="EEX LX : Learning Material">EEX LX : Learning Material</option>
                    <option value="EEX LX : Collaborations & innovation studio">EEX LX : Collaborations & innovation studio</option>
                    <option value="EEX LX : Team work 4.0">EEX LX : Team work 4.0</option>
                    <option value="EEX LX : Professional social network 4.0">EEX LX : Professional social network 4.0</option>
                    <option value="EEX LX : LiveChat / Webinars">EEX LX : LiveChat / Webinars</option>
                    <option value="EEX LX : Simplified UX">EEX LX : Simplified UX</option>
                    <option value="EEX LX : Easy Access">EEX LX : Easy Access</option>
                    <option value="EEX LX : Communication & Services Portal">EEX LX : Communication & Services Portal</option>
                    <option value="EEX LX : Ideation & Engagement Tools">EEX LX : Ideation & Engagement Tools</option>
                    <option value="SSS LX : Dispatch Reliability Advanced services">SSS LX : Dispatch Reliability Advanced services</option>
                    <option value="SSS LX : Remote inspection / remote assistance">SSS LX : Remote inspection / remote assistance</option>
                    <option value="SSS LX : 3D AR / VR Training">SSS LX : 3D AR / VR Training</option>
                    <option value="SSS LX : Advanced Contracts control system">SSS LX : Advanced Contracts control system</option>
                    <option value="SSS LX : Spare parts advanced SCM">SSS LX : Spare parts advanced SCM</option>
                    <option value="SSS LX : Spare parts data exchange">SSS LX : Spare parts data exchange</option>
                    <option value="SSS LX : Smart MRO">SSS LX : Smart MRO</option>
                    <option value="SSS LX : Sales e-tools">SSS LX : Sales e-tools</option>
                    <option value="SSS LX : RPA">SSS LX : RPA</option>
                    <option value="SSS LX : CRM">SSS LX : CRM</option>
                    <option value="SSS LX : Customer Portal">SSS LX : Customer Portal</option>
                    <option value="SSS LX : PLM">SSS LX : PLM</option>
                    <option value="SSS LX : ERP+">SSS LX : ERP+</option>
                    <option value="SSS LX : Technical Documentation">SSS LX : Technical Documentation</option>
                    <option value="Autre">Autre</option>
                    <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                    <option value="KPI Transverse">KPI Transverse</option>
                    <option value="Roadmap">Roadmap</option>
                <!-- Insérez d'autres options de levier ici -->
            </select><br>
            <!-- Sélecteur pour Triangle PCD -->
            
            <select name="trianglePCD" id="trianglePCD" required>
                <option value="">Sélectionnez un niveau du triangle PCD</option>
                <option value="Plans de transfo">1 - Plans de transfo</option>
                <option value="Plans de progrès">2 - Plans de progrès</option>
                <option value="Soucis quotidiens">3 - Soucis quotidiens</option>
            </select><br>

            <!-- Sélecteur pour Etape -->
            
            <select name="etape" id="etape" required>
                <option value="">Sélectionnez une étape</option>
                <option value="StandBy">0 - StandBy</option>
                <option value="Reflexion et cadrage">1 - Reflexion et cadrage</option>
                <option value="Test et POC">2 - Test et POC</option>
                <option value="GO/No GO et décision">3 - GO/No GO et décision</option>
                <option value="Déploiement locaux">4 - Déploiement locaux</option>
                <option value="Généralisation et standardisation">5 - Généralisation et standardisation</option>
            </select><br>

            <!-- Sélecteur pour Transverse -->
            
            <select name="transverse" id="transverse" required>
                <option value="">Sélectionnez une option transverse</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour DI -->
            
            <select name="DI" id="DI" required>
                <option value="">Sélectionnez une option DI</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour DT -->
            
            <select name="DT" id="DT" required>
                <option value="">Sélectionnez une option DT</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour SFS -->
            
            <select name="SFS" id="SFS" required>
                <option value="">Sélectionnez une option SFS</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour DC -->
            
            <select name="DC" id="DC" required>
                <option value="">Sélectionnez une option DC</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour Chantier -->
            
            <select name="chantier" id="chantier" required>
                <option value="">Sélectionnez votre chantier</option>
                <option value="One Safran">One Safran</option>
                <option value="Black Belt">Black Belt</option>
                <option value="Green Belt">Green Belt</option>
                <option value="Diag 4.0">Diag 4.0</option>
            </select><br>

            <!-- Sélecteur pour Stream 4.0 -->
           
            <select name="Stream" id="stream" required>
                <option value="">Sélectionnez votre Stream 4.0</option>
                <option value="Manufacturing 4.0">Manufacturing 4.0</option>
                <option value="Engineering 4.0">Engineering 4.0</option>
                <option value="Employee Experience">Employee Experience</option>
                <option value="Sales, Support and Services">Sales, Support and Services</option>
            </select><br>


            <button type="button" onclick="addRow()" class="button">Ajouter</button>
        </form>
    </div>

<!-- Formulaire pour supprimer une ligne (caché par défaut) -->
<div id="deleteFormContainer" class="formContainer" style="display: none;">
    <h2>Supprimer une ligne :</h2>
    <input type="number" id="deleteId" placeholder="Entrez l'ID à supprimer" required />
    <button onclick="deleteById()" class="button">Supprimer</button>
</div>

<!-- Formulaire de modification de ligne (caché par défaut) -->
<div id="editFormContainer" class="formContainer" style="display: none;">
    <h2>Modifier une ligne :</h2>
    <form id="editRowForm">
        <!-- Champ pour entrer l'ID de la ligne à modifier -->
        <input type="number" id="editRowId" name="id" placeholder="Entrez l'ID de la ligne à modifier" required /><br>
        <!-- Champs de formulaire -->
        <input type="text" name="intitule" placeholder="Intitulé" required /><br>
            <input type="text" name="objectifs" placeholder="Objectifs" required /><br>
            <input type="date" name="datededebut" placeholder="Date de début" required /><br>
            <input type="date" name="datedefin" placeholder="Date de fin" required /><br>
            <!-- Champ pour l'Avancement -->
            
            <label for="Avancement">Avancement :</label>
            <input type="range" id="addAvancement" name="avancement" min="0" max="100" value="0" oninput="avancementValueDisplay('addAvancement', this.value)" style="width: 100%;">
            <span id="addAvancementValue">0%</span><br>

            <!-- Sélecteur pour le levier -->
            
            <select name="levier" id="levier" required>
                <option value="">Sélectionnez un levier</option>
                <option value="">Tous</option>
                    <option value="MFG L1 : PLM">MFG L1 : PLM</option>
                    <option value="MFG L2 : ERP">MFG L2 : ERP</option>
                    <option value="MFG L3 : MES & D. Scheduling">MFG L3 : MES & D. Scheduling</option>
                    <option value="MFG L4 : MCS & 5G & WIFI 6E">MFG L4 : MCS & 5G & WIFI 6E</option>
                    <option value="MFG L5 : Digital Twins">MFG L5 : Digital Twins</option>
                    <option value="MFG L6 : Lean 4.0 Methods">MFG L6 : Lean 4.0 Methods</option>
                    <option value="MFG L7 : Lean 4.0 Tools">MFG L7 : Lean 4.0 Tools</option>
                    <option value="MFG L8 : Lean 4.0 Teams">MFG L8 : Lean 4.0 Teams</option>
                    <option value="MFG L9 : IT-RB & Cyber">MFG L9 : IT-RB & Cyber</option>
                    <option value="MFG L10 : Closed Door Equipment">MFG L10 : Closed Door Equipment</option>
                    <option value="MFG L11 : Manufacturing Data analysis">MFG L11 : Manufacturing Data analysis</option>
                    <option value="MFG L12 : Cobots & Robots">MFG L12 : Cobots & Robots</option>
                    <option value="MFG L13 : Augmented Reality">MFG L13 : Augmented Reality</option>
                    <option value="MFG L14 : Digital Inspection & Automated Decision">MFG L14 : Digital Inspection & Automated Decision</option>
                    <option value="MFG L15 : Marking & Traceability">MFG L15 : Marking & Traceability</option>
                    <option value="MFG L16 : IIOT">MFG L16 : IIOT</option>
                    <option value="MFG L17 : CMMS & Remote maintenance">MFG L17 : CMMS & Remote maintenance</option>
                    <option value="MFG L18 : FMS & BIM">MFG L18 : FMS & BIM</option>
                    <option value="MFG L18 : Supply Chain 4.0">MFG L18 : Supply Chain 4.0</option>
                    <option value="ENG LX : Project Management">ENG LX : Project Management</option>
                    <option value="ENG LX : Agile">ENG LX : Agile</option>
                    <option value="ENG LX : Development Cockpit & Design to Value">ENG LX : Development Cockpit & Design to Value</option>
                    <option value="ENG LX : Virtual & Augmented Reality">ENG LX : Virtual & Augmented Reality</option>
                    <option value="ENG LX : Remote collaboration">ENG LX : Remote collaboration</option>
                    <option value="ENG LX : MBSE">ENG LX : MBSE</option>
                    <option value="ENG LX : Software">ENG LX : Software</option>
                    <option value="ENG LX : Model Based Definition">ENG LX : Model Based Definition</option>
                    <option value="ENG LX : Industrialisation tools">ENG LX : Industrialisation tools</option>
                    <option value="ENG LX : Services authoring tools">ENG LX : Services authoring tools</option>
                    <option value="ENG LX : Data analytics">ENG LX : Data analytics</option>
                    <option value="ENG LX : Simulation Tools">ENG LX : Simulation Tools</option>
                    <option value="ENG LX : PLM - As-required">ENG LX : PLM - As-required</option>
                    <option value="ENG LX : PLM - As-designed">ENG LX : PLM - As-designed</option>
                    <option value="ENG LX : PLM - As-planned / As-built">ENG LX : PLM - As-planned / As-built</option>
                    <option value="ENG LX : PLM - As-to be maintained / As-maintained">ENG LX : PLM - As-to be maintained / As-maintained</option>
                    <option value="ENG LX : Simulations management">ENG LX : Simulations management</option>
                    <option value="ENG LX : Tests Management">ENG LX : Tests Management</option>
                    <option value="ENG LX : Materials and Processis Database a LIMS">ENG LX : Materials and Processis Database a LIMS</option>
                    <option value="EEX LX : New Attitudes">EEX LX : New Attitudes</option>
                    <option value="EEX LX : Leadership Model Evolution">EEX LX : Leadership Model Evolution</option>
                    <option value="EEX LX : HR processes adaptation">EEX LX : HR processes adaptation</option>
                    <option value="EEX LX : Well being @e-work">EEX LX : Well being @e-work</option>
                    <option value="EEX LX : New Internal rules">EEX LX : New Internal rules</option>
                    <option value="EEX LX : Digital Competencies">EEX LX : Digital Competencies</option>
                    <option value="EEX LX : Digital Learning">EEX LX : Digital Learning</option>
                    <option value="EEX LX : Digital resources management">EEX LX : Digital resources management</option>
                    <option value="EEX LX : Learning Material">EEX LX : Learning Material</option>
                    <option value="EEX LX : Collaborations & innovation studio">EEX LX : Collaborations & innovation studio</option>
                    <option value="EEX LX : Team work 4.0">EEX LX : Team work 4.0</option>
                    <option value="EEX LX : Professional social network 4.0">EEX LX : Professional social network 4.0</option>
                    <option value="EEX LX : LiveChat / Webinars">EEX LX : LiveChat / Webinars</option>
                    <option value="EEX LX : Simplified UX">EEX LX : Simplified UX</option>
                    <option value="EEX LX : Easy Access">EEX LX : Easy Access</option>
                    <option value="EEX LX : Communication & Services Portal">EEX LX : Communication & Services Portal</option>
                    <option value="EEX LX : Ideation & Engagement Tools">EEX LX : Ideation & Engagement Tools</option>
                    <option value="SSS LX : Dispatch Reliability Advanced services">SSS LX : Dispatch Reliability Advanced services</option>
                    <option value="SSS LX : Remote inspection / remote assistance">SSS LX : Remote inspection / remote assistance</option>
                    <option value="SSS LX : 3D AR / VR Training">SSS LX : 3D AR / VR Training</option>
                    <option value="SSS LX : Advanced Contracts control system">SSS LX : Advanced Contracts control system</option>
                    <option value="SSS LX : Spare parts advanced SCM">SSS LX : Spare parts advanced SCM</option>
                    <option value="SSS LX : Spare parts data exchange">SSS LX : Spare parts data exchange</option>
                    <option value="SSS LX : Smart MRO">SSS LX : Smart MRO</option>
                    <option value="SSS LX : Sales e-tools">SSS LX : Sales e-tools</option>
                    <option value="SSS LX : RPA">SSS LX : RPA</option>
                    <option value="SSS LX : CRM">SSS LX : CRM</option>
                    <option value="SSS LX : Customer Portal">SSS LX : Customer Portal</option>
                    <option value="SSS LX : PLM">SSS LX : PLM</option>
                    <option value="SSS LX : ERP+">SSS LX : ERP+</option>
                    <option value="SSS LX : Technical Documentation">SSS LX : Technical Documentation</option>
                    <option value="Autre">Autre</option>
                    <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                    <option value="KPI Transverse">KPI Transverse</option>
                    <option value="Roadmap">Roadmap</option>
                <!-- Insérez d'autres options de levier ici -->
            </select><br>
            <!-- Sélecteur pour Triangle PCD -->
            
            <select name="trianglePCD" id="trianglePCD" required>
                <option value="">Sélectionnez un niveau du triangle PCD</option>
                <option value="Plans de transfo">1 - Plans de transfo</option>
                <option value="Plans de progrès">2 - Plans de progrès</option>
                <option value="Soucis quotidiens">3 - Soucis quotidiens</option>
            </select><br>

            <!-- Sélecteur pour Etape -->
            
            <select name="etape" id="etape" required>
                <option value="">Sélectionnez une étape</option>
                <option value="StandBy">0 - StandBy</option>
                <option value="Reflexion et cadrage">1 - Reflexion et cadrage</option>
                <option value="Test et POC">2 - Test et POC</option>
                <option value="GO/No GO et décision">3 - GO/No GO et décision</option>
                <option value="Déploiement locaux">4 - Déploiement locaux</option>
                <option value="Généralisation et standardisation">5 - Généralisation et standardisation</option>
            </select><br>

            <!-- Sélecteur pour Transverse -->
            
            <select name="transverse" id="transverse" required>
                <option value="">Sélectionnez une option transverse</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour DI -->
            
            <select name="DI" id="DI" required>
                <option value="">Sélectionnez une option DI</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour DT -->
            
            <select name="DT" id="DT" required>
                <option value="">Sélectionnez une option DT</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour SFS -->
            
            <select name="SFS" id="SFS" required>
                <option value="">Sélectionnez une option SFS</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour DC -->
            
            <select name="DC" id="DC" required>
                <option value="">Sélectionnez une option DC</option>
                <option value="Pulse">1 - Pulse</option>
                <option value="NexStep">2 - NexStep</option>
                <option value="Target">3 - Target</option>
                <option value="Evolve">4 - Evolve</option>
            </select><br>

            <!-- Sélecteur pour Chantier -->
            
            <select name="chantier" id="chantier" required>
                <option value="">Sélectionnez votre chantier</option>
                <option value="One Safran">One Safran</option>
                <option value="Black Belt">Black Belt</option>
                <option value="Green Belt">Green Belt</option>
                <option value="Diag 4.0">Diag 4.0</option>
            </select><br>

            <!-- Sélecteur pour Stream 4.0 -->
           
            <select name="Stream" id="stream" required>
                <option value="">Sélectionnez votre Stream 4.0</option>
                <option value="Manufacturing 4.0">Manufacturing 4.0</option>
                <option value="Engineering 4.0">Engineering 4.0</option>
                <option value="Employee Experience">Employee Experience</option>
                <option value="Sales, Support and Services">Sales, Support and Services</option>
            </select><br>


            <button type="button" onclick="editRow()" class="button">Ajouter</button>
        </form>
    </div>

<script>
// JavaScript pour gérer les actions du formulaire
function toggleAddForm() {
    var addFormContainer = document.getElementById("addFormContainer");
    var deleteFormContainer = document.getElementById("deleteFormContainer");

    addFormContainer.style.display = addFormContainer.style.display === "none" ? "block" : "none";
    // Assurez-vous de cacher le formulaire de suppression si le formulaire d'ajout est affiché
    if (addFormContainer.style.display === "block") {
        deleteFormContainer.style.display = "none";
        editFormContainer.style.display = "none";
    }
}

function toggleDeleteForm() {
    var deleteFormContainer = document.getElementById("deleteFormContainer");
    var addFormContainer = document.getElementById("addFormContainer");
    var editFormContainer = document.getElementById("editFormContainer"); // Obtenez le conteneur de formulaire de modification

    deleteFormContainer.style.display = deleteFormContainer.style.display === "none" ? "block" : "none";
    
    // Cacher les autres formulaires
    if (deleteFormContainer.style.display === "block") {
        addFormContainer.style.display = "none";
        editFormContainer.style.display = "none"; // Cachez le formulaire de modification
    }
}

function toggleEditForm() {
    var editFormContainer = document.getElementById("editFormContainer");
    var addFormContainer = document.getElementById("addFormContainer");
    var deleteFormContainer = document.getElementById("deleteFormContainer"); // Obtenez le conteneur de formulaire de suppression

    editFormContainer.style.display = editFormContainer.style.display === "none" ? "block" : "none";
    
    // Cacher les autres formulaires
    if (editFormContainer.style.display === "block") {
        addFormContainer.style.display = "none";
        deleteFormContainer.style.display = "none"; // Cachez le formulaire de suppression
    }
}


function editRow() {
    var formData = new FormData(document.getElementById("editRowForm"));
    fetch("update_row.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Ou mettre à jour l'affichage de la ligne modifiée
        location.reload(); // Pour l'instant, recharge la page pour voir les changements
    })
    .catch(error => console.error('Error:', error));
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
    fetch("insert_row_admin.php", {
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


// ... Reste des fonctions JavaScript ...

</script>

<?php
$conn->close();
?>

</body>
</html>
