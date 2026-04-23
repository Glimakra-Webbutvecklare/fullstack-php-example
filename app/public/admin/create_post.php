<?php
declare(strict_types=1);
require_once '../includes/config.php';

// skydda routen, det ska endast vara inloggande användare
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// hämta hem inloggad användares id
$logged_in_user_id = $_SESSION['user_id'];

// databaskontakt
require_once '../includes/database.php';
// post modell för att skapa ny post
require_once '../includes/Post.php';
$post_model = new Post(connect_db());

// variabler för formuläret
$errors = [];
$title = [];
$body = '';

// ta emot en data från formuläret
// dvs undersöka om requesten är POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']) ?? '';
    $body = trim($_POST['body']) ?? '';

    // Validera så att title och body är ej tomma
    if (empty($title)) {
        $errors = ['Titel kan inte vara tom'];
    }
    if (empty($body)) {
        $errors = ['Innehåll kan inte vara tom'];
    }
    // om både title och body är OK så skapa en ny post med
    if (empty($errors)) {
        try {
            // skapa en post
            $post_model->create($logged_in_user_id, $title, $body);

            // redirect till admin/index.php och lägg till ?created=success
            header('Location: index.php?created=success');
        } catch (PDOException $e) {
            error_log("Create Post error", $e->getMessage());
            $errors[] = 'Databasfel. Kan inte spara inlägg just nu.';
        }

    }
    

    // fånga eventuella fel
}

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skapa nytt inlägg - Admin</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea {
            width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box;
        }
        textarea { min-height: 150px; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
        .error-messages { color: red; margin-bottom: 15px; }
        .error-messages ul { list-style: none; padding: 0; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Skapa nytt blogginlägg</h1>
    <?php if (!empty($errors)): ?>
    <div class="error-messages">
        <strong>Inlägget kunde inte sparas:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <p><a href="index.php">&laquo; Tillbaka till Admin Dashboard</a></p>

    <form action="create_post.php" method="post">
        <div class="form-group">
            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="body">Innehåll:</label>
            <textarea id="body" name="body" required></textarea>
        </div>
        <button type="submit">Spara inlägg</button>
    </form>
</body>
</html>