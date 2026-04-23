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

$created_post_success = isset($_GET['created']) && $_GET['created'] === 'success';

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Admin - Enkel Blogg</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box;
        }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error-messages { color: red; margin-bottom: 15px; }
        .error-messages ul { list-style: none; padding: 0; }
        .success-message { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <?php if ($created_post_success): ?>
        <p class="success-message">Inlägg har skapats.</p>
    <?php endif; ?> 
    <p>Välkommen, <?php echo htmlspecialchars($logged_in_username); ?>!</p>
    <p><a href="create_post.php">Skapa nytt inlägg</a></p>
    <p><a href="../index.php">Visa blogg</a> | <a href="../logout.php">Logga ut</a></p>
</body>
</html>