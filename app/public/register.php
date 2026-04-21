<?php
declare(strict_types=1);
require_once 'includes/config.php';
require_once 'includes/database.php';

// Variabler som används senare
$errors = [];
$username = '';
$email = '';

// Hantera en POST request
// dvs en användare skickara data via formuläret
// med HTTP POST metoden

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /// hämta ut datan ur formuläret
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validering
    if (empty($username)) {
        $errors[] = 'Användarnamn är obligatoriskt.';
    }
    if (empty($email)) {
        $errors[] = 'E-post är obligatoriskt.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Ogiltig e-postadress.';
    }
    if (empty($password)) {
        $errors[] = 'Lösenord är obligatoriskt.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Lösenordet måste vara minst 6 tecken långt.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Lösenorden matchar inte.';
    }

    if (empty($errors)) {
        try {
            /// hämta pdo
            $pdo = connect_db();

            /// hasha lösenord
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // create new user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password_hash', $password_hash);

            if ($stmt->execute()) {
                /// redirect till login
                header('Location: login.php');
                exit;
            } else {
                /// om något går fel, meddela användaren
                $errors[] = 'Ett fel uppstod vid registrering. Försök igen.';
            }
        } catch (PDOException $e) {
            error_log("Registration Error: " . $e->getMessage());
            $errors[] = 'Databasfel. Kan inte registrera användare just nu.';
        }
    }

    /// verifera att datan är korrekt t.ex bekräfta lösenord
}

?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrera dig - Enkel Blogg</title>
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
    </style>
</head>
<body>
    <h1>Registrera nytt konto</h1>
    <?php if (!empty($errors)): ?>
    <div class="error-messages">
        <strong>Registreringen misslyckades:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="username">Användarnamn:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
        </div>
        <div class="form-group">
            <label for="email">E-post:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <div class="form-group">
            <label for="password">Lösenord:</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Bekräfta lösenord:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>
        <button type="submit">Registrera</button>
    </form>

    <p>Har du redan ett konto? <a href="login.php">Logga in här</a>.</p>
</body>
</html>