<?php
declare(strict_types=1);
require_once '../includes/config.php'; // återuppta sessionen

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$logged_in_user_id = $_SESSION['user_id'];
$logged_in_username = $_SESSION['username'];  // För att visa "Inloggad som ..."

require_once '../includes/database.php';

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Admin - Enkel Blogg</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Välkommen, <?php echo htmlspecialchars($logged_in_username); ?>!</p>
    <p><a href="create_post.php">Skapa nytt inlägg</a></p>
    <p><a href="../index.php">Visa blogg</a> | <a href="../logout.php">Logga ut</a></p>
</body>
</html>