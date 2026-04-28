<?php
declare(strict_types=1);

// Hämta config databasuppgifter
require_once '../includes/config.php';

// Säkerställa att användaren är inloggad
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?redirect='. urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$logged_in_user_id = $_SESSION['user_id'];

require_once '../includes/database.php';
require_once '../includes/Post.php';

// array för att spara eventuella fel
$errors = [];
//         $_GET['id']; // fungerar också men du har ingen validering
$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // vilken post ska uppdateras?
$post = null;

// Variabler som vi ska plocka ut från post efter att den har hämtats från DB
$title = '';
$body = '';
$current_image_path = null;

if ($post_id === false || $post_id <= 0) {
    $errors[] = "Ogiltigt post-id";
} else {
    try {

        // Försök hämta post från DB
        // post model
        $post_model = new Post(connect_db());
        // hämta posten med rätt id
        $post = $post_model->showOne($post_id);

        if (!$post) {
            $errors[] = "Posten hittade inte";
        } elseif ($post['user_id'] != $logged_in_user_id) {
            $errors[] = "Du har inte rätt att redigera posten.";
            $post = null;
        } else {
            $title = $post['title'];
            $body = $post['body'];
            $current_image_path = $post['image_path'];
        }
    } catch (PDOExepction $e) {
        error_log("Edit Post Fetch error", $e->getMessage());
        $errors[] = 'Databasfel. Kan inte hämta post för redigering.';
        $post = null;
    }
}

// Hantera en förfrågan från formuläret
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_title = trim($_POST['title'] ?? '');
    $new_body = trim($_POST['body'] ?? '');
    $new_image_path = $current_image_path; // TODO: fix

    // validera att datan är rimlig
    if (empty($new_title)) {
        $errors[] = 'Titel är obligatoriskt.';
    }
    if (empty($new_body)) {
        $errors[] = 'Innehåll är obligatoriskt.';
    }

    if (empty($errors)) {
        try {
            // Försök uppdatera posten
            $post_model = new Post(connect_db());
            $updated_successfully = $post_model->updateOne($post_id, 
                                                            $logged_in_user_id, 
                                                            $new_title, 
                                                            $new_body, 
                                                            $new_image_path);
            
            if ($updated_successfully) {
                header("Location: index.php?updated=success&id=" . $post_id);
                exit;
            } else {
                $errors[] = 'Ett fel uppstod när inlägget skulle uppdateras.';
            }
        } catch (PDOException $e) {
            error_log("Update Post Error: " . $e->getMessage());
            $errors[] = 'Databasfel. Kan inte uppdatera inlägg just nu.';
        }
    }

}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redigera inlägg - Admin</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], textarea {
            width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box;
        }
        textarea { min-height: 150px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        .error-messages { color: red; margin-bottom: 15px; }
        .error-messages ul { list-style: none; padding: 0; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Redigera blogginlägg</h1>
    <p><a href="index.php">&laquo; Tillbaka till Admin Dashboard</a></p>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <strong>Inlägget kunde inte uppdateras:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($post): ?>
        <form action="edit_post.php?id=<?php echo $post_id; ?>" method="post">
            <?php if ($current_image_path): ?>
                <div>
                    <img src="<?= htmlspecialchars(BASE_URL . '/' . $current_image_path); ?>" alt="">
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="title">Titel:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="form-group">
                <label for="body">Innehåll:</label>
                <textarea id="body" name="body" required><?php echo htmlspecialchars($body); ?></textarea>
            </div>
            <button type="submit">Uppdatera inlägg</button>
        </form>
    <?php endif; ?>
</body>
</html>