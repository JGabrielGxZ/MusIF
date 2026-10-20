<?php
    require_once __DIR__ . "/config/config.php";
    include_once 'config/db.php';
    if (!isset($_SESSION['matricula'])) {
        header('Location: views/login.php');
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/Musif/controllers/logout.php" method="post">
        <button type="submit">Sair</button>
    </form>

    <header>

    </header>

    <main>

    
    <aside>

    </aside>

    <article>
        <section>

        </section>
    </article>

    </main>

    <footer>

    </footer>
</body>
</html>