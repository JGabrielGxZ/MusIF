<?php
    require_once __DIR__ . "/config/config.php";
    include_once 'config/db.php';
    if (!isset($_SESSION['matricula'])) {
        header('Location: views/login.php');
        exit;
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MusIF - Home</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <form action="/Musif/controllers/logout.php" method="post">
        <button type="submit">Sair</button>
    </form>

    <header>
        <div class="logo-index-1">
            <i class="bi bi-music-note-list" class="icone_logo-index-1"></i>
            <h1 class="titulo-index-1">MusIF</h1>
        </div>
    </header>

    <main>

    
    <aside class="aside-index-1">
        <nav>
            <div class="nav-index-1">
                <a href=""><i class="bi bi-house-fill"></i>Início</a>
                <a href=""><i class="bi bi-music-note-beamed"></i>Instrumentos</a>
                <a href=""><i class="bi bi-repeat"></i>Empréstimos</a>
                <a href=""><i class="bi bi-gear-fill"></i>Configurações</a>
            </div>
        </nav>
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