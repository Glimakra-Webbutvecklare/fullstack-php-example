<?php
declare(strict_types=1);
$title = "Hello world";

// phpinfo();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>

    <link rel="stylesheet" href="styles/style.css">

</head>
<body>

    <?php
    include "includes/header.php";
    ?>

    <h1><?= $title ?></h1>

    <?php
    include "includes/nav.php";
    ?>

</body>
</html>