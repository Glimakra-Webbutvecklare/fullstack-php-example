<!-- navigering mellan olika filer / sidor -->
<nav>
    <a href="/">Startsida</a>
    <a href="/register.php">Registrera</a>

    <!-- om man är inloggad ska man kunna logga ut 
     annars visas länk till att logga in
    -->

    <?php

    if (isset($_SESSION['user_id'])) {
        echo '<a href="logout.php" class="dark-mode">Logga ut</a>';
    } else {
        echo '<a href="/login.php">Login</a>';
    }

    ?>

    

    <!-- visa om man är inloggad -->

    <?php

    if (isset($_SESSION['user_id'])) {

        // printa användarnamn
        echo  $_SESSION['username'];
    }


    ?>

</nav>