<?php
    include_once "../config/db.php";

    $db = new db();
    $conn = $db->conectar();

    $email = $_POST['email'];
    $pass = $_POST['pass'];

    try {
        $sql = "INSERT INTO pessoa(email, pass) values (:e, :p)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':e', $email);
        $stmt->bindParam(':p', $pass);
        
        if ($stmt->execute()) {
            echo "Deu certo!";
        }
    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>