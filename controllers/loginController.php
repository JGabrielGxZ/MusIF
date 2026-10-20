<?php
    require_once __DIR__ . "/../config/db.php";
    require_once __DIR__ ."/../config/config.php";
    require_once __DIR__ . "/../models/usuarioModel.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mat = $_POST['mat'];
        $pass = $_POST['pass'];

        if (!empty($mat) && !empty($pass)) {
            $database = new db();
            $conn = $database->conectar();
            $usuario = new UsuarioModel($conn);

            $resultado = $usuario->login($mat, $pass);
            

            if ($resultado !== false) {
                $_SESSION['matricula'] = $resultado['matricula'];
                
                header('Location: ../index.php');
                exit;
            } else {
                echo "Matricula ou senha incorreta.";
            }
        } else {
            echo "Preencha a matricula e a senha";
        }
    }
    
?>