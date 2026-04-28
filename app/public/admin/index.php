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
require_once '../includes/Post.php'; // visa inlogg admins egna posts

$posts = [];
$fetch_error = null;
$success_message = null;
$post_model = new Post(connect_db());


// status-meddelande som vi visar efter utförd handling
if (isset($_GET['created']) && $_GET['created'] === 'success') {
    $success_message = "Nytt inlägg skapat!";
} elseif (isset($_GET['updated']) && $_GET['updated'] === 'success') {
    $success_message = "Inlägg uppdaterat!";
} elseif (isset($_GET['deleted']) && $_GET['deleted'] === 'success') {
    $success_message = "Inlägg raderat!";
}

try {
    // Hämta alla posts från given användare 
    $posts = $post_model->showAllByUser($logged_in_user_id);

    //print_r($posts);
} catch (PDOException $e) {
    error_log("Admin Index Error: " . $e->getMessage());
    $fetch_error = "Kunde inte hämta dina blogginlägg just nu.";
}

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Admin - Enkel Blogg</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        nav { margin-bottom: 20px; background-color: #f8f9fa; padding: 10px; border-radius: 5px; }
        nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .create-link { display: inline-block; margin-bottom: 20px; background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
        .create-link:hover { background-color: #218838; }
        .error-message { color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; }
        .success-message { color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <?php include '../includes/nav.php' ?>

    <h1>Admin Dashboard</h1>
    <p>Välkommen, <?php echo htmlspecialchars($logged_in_username); ?>!</p>

    <p><a href="create_post.php">Skapa nytt inlägg</a></p>
    
    <?php if ($success_message): ?>
        <p class="success-message"><?= $success_message ?></p>
    <?php endif; ?>

    <?php if ($fetch_error): ?>
        <p class="error-message"><?php echo htmlspecialchars($fetch_error); ?></p>
    <?php elseif (empty($posts)): ?>
        <p>Du har inte skapat några inlägg ännu.</p>
    <?php else: ?>
        <h2>Dina Inlägg</h2>
        <table>
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Skapad</th>
                    <th>Senast ändrad</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>

            <tbody>
                 <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($post['updated_at'])); ?></td>
                        <td>
                            <a href="../post.php?id=<?php echo $post['id']; ?>" target="_blank">Visa</a>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>">Redigera</a>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>">Delete</a>
                            <!-- Radera-knapp kommer i steg 4 -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>