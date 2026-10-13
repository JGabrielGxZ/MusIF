```php
<?php
    class db {
        // Dados necessários para acessar o nosso banco de dados
        private $host = "localhost";
        private $user = "root";
        private $pass = "";
        private $dbname = "musifdb";

        // Variável que vai guardar a conexão com o banco
        public $conn;

        // Função responsável por conectar nosso sistema ao banco de dados
        public function conectar() {

            // O try tenta executar a conexão normalmente
            try {

                // Cria a conexão usando PDO
                // Aqui informamos o tipo de banco, o endereço, o nome do banco,
                // o charset (para aceitar acentos e caracteres especiais),
                // o usuário e a senha
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4", $this->user, $this->pass
                );

                // Faz com que os erros do banco sejam tratados como exceções
                // Assim, se algo der errado, o PHP consegue identificar o erro
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Devolve a conexão para que outras partes do sistema possam usá-la
                return $this->conn;

            // Se ocorrer um erro durante a conexão, ele será capturado aqui
            // A variável $e guarda as informações sobre o erro
            } catch (PDOException $e) {

                // Interrompe a execução e mostra a mensagem do erro
                // Isso ajuda a descobrir o que deu errado durante o desenvolvimento
                die($e->getMessage());
            }
        }
    }
?>
```