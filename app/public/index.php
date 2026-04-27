<?php
declare(strict_types=1);
require_once "includes/database.php";
include_once "includes/functions.php";


// visa den globala sessionen som servern har koll på
// print_r($_SESSION);

// i php finns flera liknande arrayer som talar om 
// diverse variabler, ex hur är servern konfigurerar

// print_r2($_SERVER);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Min blogg app</title>

    <?php

    // filer _setup.php lägger till en querystring om tabeller installerades
    // använd $_GET
    if (isset($_GET['setup']) && $_GET['setup'] == "true") {
        echo '<p>Installationen av applikationen är gjord.</p>';
    }

    ?>


    <link rel="stylesheet" href="styles/style.css">

</head>
<body>

    <?php include "includes/nav.php" ?>

    <h1>Min blog app</h1>



</body>
</html>