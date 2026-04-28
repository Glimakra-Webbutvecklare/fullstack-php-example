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

$post_model = new Post(connect_db());

$id_to_delete = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);//$_GET['id'];

echo "User: $logged_in_user_id wants to delete post $id_to_delete";

$deleted_successfully = $post_model->deleteOne($id_to_delete, $logged_in_user_id);

if ($deleted_successfully) {
    header("Location: index.php?deleted=success");
    exit;
}

// visa upp felmeddelat