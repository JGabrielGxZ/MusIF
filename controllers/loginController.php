<?php
    include_once  "../config/db.php";


    $mat = $_POST['mat'];
    $pass = $_POST['pass'];

    if (!empty($mat) && !empty($pass)) {
        $database = new db();
        $conn = $database->conectar();
        $usuario = new UsuarioModel($conn);
    }
?>