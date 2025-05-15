<?php
declare(strict_types=1);
$title = "Hello world";

// phpinfo();

// Test database connection
$db_root_password = getenv('MYSQL_ROOT_PASSWORD');
$db_user = getenv('MYSQL_USER');
$db_password = getenv('MYSQL_PASSWORD');
$db_database = getenv('MYSQL_DATABASE');

if (!$db_root_password || !$db_user || !$db_password || !$db_database) {
    echo 'DB_CONNECTION_STRING environment variable not set.';
}
$db_connection_string = "mysql:host=mysql;port=3306;dbname=$db_database;user=$db_user;password=$db_password";

try {
    $pdo = new PDO($db_connection_string);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create a test table
    $pdo->exec("CREATE TABLE IF NOT EXISTS test (id SERIAL PRIMARY KEY, name VARCHAR(255))");
    $pdo->exec("INSERT INTO test (name) VALUES ('Hello, world!')");

    // Query the test table
    $result = $pdo->query("SELECT * FROM test");
    $row = $result->fetch();
    $name = $row['name'];
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
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

    <p>Data from database: <?= $name ?></p>

</body>
</html>