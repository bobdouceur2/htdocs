<?php

// chemin : http://localhost/PHP_esme/exemple.php

session_start();
$id_session = session_id();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head> 
    <body>
        <h1>Titre Principal</h1>
        <?php
            if($id_session){
                echo 'ID de session'.$id_session. '<br>';


            }
        ?>
        <p>Bienvenue à notre page web avec session </p>
    </body>
</html>