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
    <header>
        <div class="logo-index-1">
            <i class="bi bi-music-note-list" class="icone_logo-index-1"></i>
            <h1 class="titulo-index-1">MusIF</h1>
        </div>
    </header>

    <main>

    
    <aside class="aside-index-1">
        <nav class="nav-index-1">
            <div class="links_top-index-1">
                <a href=""><i class="bi bi-house-fill"></i>Início</a>
                <a href=""><i class="bi bi-music-note-beamed"></i>Instrumentos</a>
                <a href=""><i class="bi bi-repeat"></i>Empréstimos</a>
                <a href=""><i class="bi bi-gear-fill"></i>Configurações</a>
            </div>

            <div class="links_down-index">
                <form action="/Musif/controllers/logout.php" method="post">
                    <button type="submit"><i class="bi bi-door-closed"></i></button>
                </form>
            </div>
            <script>
            
            </script>
        </nav>
    </aside>

    <article class="article-index-1">
        <section class="section-index-1">

        </section>
    </article>

    </main>

    <footer>
        
    </footer>
</body>
<script src="assets/js/index.js"></script>
</html>