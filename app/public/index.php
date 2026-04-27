<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/Post.php';

$posts = [];
$fetch_error = null;

try {
    $post_model = new Post(connect_db());
    $posts = $post_model->showAll();
} catch (PDOException $e) {
    error_log("Index Page Error: " . $e->getMessage());
    $fetch_error = "Kunde inte hämta blogginlägg just nu. Försök igen senare.";
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enkel Blogg</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .post-summary { border: 1px solid #eee; padding: 15px; margin-bottom: 20px; }
        .post-summary h2 { margin-top: 0; }
        .post-meta { font-size: 0.9em; color: #666; margin-bottom: 10px; }
        .post-image-list { max-width: 150px; max-height: 100px; float: right; margin-left: 15px; }
        nav { margin-bottom: 20px; background-color: #f8f9fa; padding: 10px; border-radius: 5px; }
        nav a { margin-right: 15px; text-decoration: none; color: #007bff; }
        nav a:hover { text-decoration: underline; }
        .error-message { color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; }
        .success-message { color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Hem</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="admin/index.php">Admin Dashboard</a>
            <a href="logout.php">Logga ut (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="login.php">Logga in</a>
            <a href="register.php">Registrera dig</a>
        <?php endif; ?>
    </nav>

    <h1>Välkommen till Bloggen!</h1>

    <?php if (isset($_GET['logged_out']) && $_GET['logged_out'] === 'success'): ?>
        <p class="success-message">Du har loggats ut.</p>
    <?php endif; ?>

    <?php if ($fetch_error): ?>
        <p class="error-message"><?php echo htmlspecialchars($fetch_error); ?></p>
    <?php elseif (empty($posts)): ?>
        <p>Det finns inga blogginlägg ännu.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="post-summary">
                <?php if (!empty($post['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars(BASE_URL . '/' . $post['image_path']); ?>"
                         alt="Inläggsbild" class="post-image-list">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <div class="post-meta">
                    Publicerad: <?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?>
                    av <?php echo htmlspecialchars($post['username']); ?>
                </div>
                <p>
                    <?php
                    $summary = htmlspecialchars($post['body']);
                    if (strlen($summary) > 200) {
                        $summary = substr($summary, 0, 200) . '...';
                    }
                    echo nl2br($summary);
                    ?>
                </p>
                <a href="post.php?id=<?php echo $post['id']; ?>">Läs mer &raquo;</a>
                <div style="clear: both;"></div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
