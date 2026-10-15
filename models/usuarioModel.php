<?php
    include_once "../config/db.php";
    class UsuarioModel{
        private PDO $conn;
        private $matricula;
        private $senha;

        public function __construct(PDO $conn, string $matricula, string $senha) {
            $this->conn = $conn;
            $this->matricula = $matricula;
            $this->senha = $senha;
        }
        public function login() {
            $sql = "SELECT * FROM usuario WHERE matricula = :m";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":m", $this->matricula);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $usuario = $stmt->fetch();
                $usuario[''];
            }
        }

    }


?>