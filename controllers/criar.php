<?php

    include_once "../config/db.php";

    $database = new db();
    $conn = $database->conectar();

    $email = $_POST['email'];
    $pass = $_POST['pass'];

    try {
        $sql = "INSERT INTO pessoa(email, pass) values (:e, :p)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':e', $email);
        $stmt->bindParam(':p', $pass);
        
        if ($stmt->execute()) {
            echo "deu certo";
        }

    } catch (PDOException $e) {
        die("deu erro: " . $e->getMessage());
    }
?>