<?php
declare(strict_types=1);
require_once '../includes/config.php';

// kontroll av inloggad
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$logged_in_user_id = $_SESSION['user_id'];

require_once '../includes/database.php';
require_once '../includes/Post.php';

$errors = [];

// Bara ta bort om förfrågan kommer med en POST metod
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // hämta ut post id från body
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);//$_POST['post_id'];

    // kontroll att post id giltig
    if ($post_id === false || $post_id <= 0) {
        $errors[] = "Ogiltigt inläggs-ID.";
    } else {
        // Försök kontakta DB och ta bort
        try {
            $post_model = new Post(connect_db());

            // 1. kolla om posten finns
            $post = $post_model->showOne($post_id);

            if (!$post) {
                $errors[] = "Inlägget hittades inte.";
            } elseif ($post['user_id'] != $logged_in_user_id) {
                $errors[] = "Du har inte behörighet att radera detta inlägg.";
            } else {
                // Ok att ta bort
                $deleted_successfully = $post_model->deleteOne($post['id'], $logged_in_user_id);
                if ($deleted_successfully) {
                    // ta bort från filsystemet
                    $image_to_delete = $post['image_path'];
                    if ($image_to_delete && file_exists(UPLOAD_PATH . basename($image_to_delete))) {
                        unlink(UPLOAD_PATH . basename($image_to_delete));
                    }

                    // Nu kan vi redirecta
                    header("Location: index.php?deleted=success");
                    exit;
                } else {
                    $errors[] = "Kunde inte radera inlägget.";
                }

            }
        } catch (PDOException $e) {
            error_log("Delete Post Error: " . $e->getMessage());
            $errors[] = 'Databasfel. Kan inte radera inlägg just nu.';
        }
    }
} else {
    $errors[] = "Ogiltig förfrågan. Radering kräver POST.";
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Fel vid radering - Admin</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .error-messages { color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px; }
        .error-messages ul { list-style: none; padding: 0; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Fel vid radering</h1>
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <p><a href="index.php">&laquo; Tillbaka till Admin Dashboard</a></p>
</body>
</html>