<?php
    include_once "../config/db.php";
    class UsuarioModel{
        private PDO $conn;

        public function __construct(PDO $conn) {
            $this->conn = $conn;
        }
        public function login(string $mat, string $pass) {
            $sql = "SELECT * FROM usuario WHERE matricula = :m";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":m", $mat);
            $stmt->execute();
            $usuario = $stmt->fetch();
            if ($usuario !== false) {
                if ($usuario['senha'] === $pass) {
                    $_SESSION['matricula'] = $mat;
                    return true;
                }
            }
        }

    }


?>