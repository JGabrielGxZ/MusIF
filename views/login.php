<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MusIF-Login</title>
</head>
<body>
    <h1>Login:</h1>

    <form action="../controllers/loginController.php" method="post">
        <label for="mat-input">matricula:</label>
        <input type="text" name="mat" id="mat-input" placeholder="digite sua Matricula!" required>
        <label for="pass-input">senha:</label>
        <input type="password" name="pass" id="pass-input" placeholder="digite sua senha!" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>