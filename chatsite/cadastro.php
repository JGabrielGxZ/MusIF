
<?php
session_start();
require_once 'conexao.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erro = '';
$sucesso = '';

$categorias = $pdo->query(
    'SELECT id, nome FROM categorias ORDER BY nome'
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Verifica o token de segurança do formulário.
        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals(
                $_SESSION['csrf_token'],
                $_POST['csrf_token']
            )
        ) {
            throw new RuntimeException(
                'Formulário expirado. Atualize a página.'
            );
        }

        // 2. Recebe e valida os dados.
        $categoriaId = filter_input(
            INPUT_POST,
            'categoria_id',
            FILTER_VALIDATE_INT
        );

        $nome = trim($_POST['nome'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $estado = $_POST['estado'] ?? '';
        $quantidade = filter_input(
            INPUT_POST,
            'quantidade',
            FILTER_VALIDATE_INT
        );
        $cores = $_POST['cores'] ?? [];

        $estadosValidos = [
            'Bom',
            'Regular',
            'Ruim',
            'A verificar'
        ];

        if (!$categoriaId || $nome === '') {
            throw new RuntimeException(
                'Selecione uma categoria e informe o instrumento.'
            );
        }

        if (
            !$quantidade ||
            $quantidade < 1 ||
            $quantidade > 100
        ) {
            throw new RuntimeException(
                'A quantidade deve estar entre 1 e 100.'
            );
        }

        if (!in_array($estado, $estadosValidos, true)) {
            throw new RuntimeException(
                'Estado de conservação inválido.'
            );
        }

        if (
            !is_array($cores) ||
            count($cores) !== $quantidade
        ) {
            throw new RuntimeException(
                'Informe a cor de cada unidade.'
            );
        }

        $cores = array_map(
            fn($cor) => trim((string) $cor),
            $cores
        );

        foreach ($cores as $cor) {
            if (mb_strlen($cor) > 50) {
                throw new RuntimeException(
                    'Cada cor deve ter no máximo 50 caracteres.'
                );
            }
        }

        if (
            mb_strlen($nome) > 100 ||
            mb_strlen($marca) > 100 ||
            mb_strlen($modelo) > 100
        ) {
            throw new RuntimeException(
                'Um dos campos ultrapassou o tamanho permitido.'
            );
        }

        // 3. Inicia a transação.
        $pdo->beginTransaction();

        // 4. Confirma que a categoria existe.
        $stmt = $pdo->prepare(
            'SELECT id FROM categorias WHERE id = ?'
        );
        $stmt->execute([$categoriaId]);

        if (!$stmt->fetch()) {
            throw new RuntimeException(
                'A categoria selecionada não existe.'
            );
        }

        // 5. Cria o tipo uma vez ou reutiliza o existente.
        // Marca/modelo vazios representam dados não informados.
        $stmt = $pdo->prepare(
            'INSERT INTO tipos_instrumentos
                (categoria_id, nome, marca, modelo)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)'
        );

        $stmt->execute([
            $categoriaId,
            $nome,
            $marca,
            $modelo
        ]);

        $tipoId = (int) $pdo->lastInsertId();

        // 6. Prepara o cadastro das unidades.
        $stmtUnidade = $pdo->prepare(
            'INSERT INTO unidades_instrumentos
                (tipo_id, patrimonio, cor, estado_conservacao, status)
             VALUES (?, NULL, ?, ?, ?)'
        );

        $stmtPatrimonio = $pdo->prepare(
            'UPDATE unidades_instrumentos
             SET patrimonio = ?
             WHERE id = ?'
        );

        // 7. Cria uma unidade para cada instrumento físico.
        for ($i = 0; $i < $quantidade; $i++) {
            $cor = $cores[$i] !== ''
                ? $cores[$i]
                : 'Não informada';

            $stmtUnidade->execute([
                $tipoId,
                $cor,
                $estado,
                'Disponível'
            ]);

            $unidadeId = (int) $pdo->lastInsertId();

            // O patrimônio deriva do ID único da unidade.
            $patrimonio = 'INS-' . str_pad(
                (string) $unidadeId,
                6,
                '0',
                STR_PAD_LEFT
            );

            $stmtPatrimonio->execute([
                $patrimonio,
                $unidadeId
            ]);
        }

        // 8. Confirma tudo de uma vez.
        $pdo->commit();

        $sucesso = $quantidade .
            ' unidade(s) cadastrada(s) com sucesso!';

        // Evita reenvio do mesmo formulário ao atualizar a página.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        if ($e instanceof RuntimeException) {
            $erro = $e->getMessage();
        } else {
            error_log($e->getMessage());
            $erro = 'Não foi possível concluir o cadastro. ' .
                'Nenhuma alteração da transação foi mantida.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de instrumentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container">
    <h1>Cadastro de instrumentos</h1>
    <p class="subtitulo">
        Cadastre um tipo e gere várias unidades físicas.
    </p>

    <?php if ($erro !== ''): ?>
        <div class="mensagem erro">
            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($sucesso !== ''): ?>
        <div class="mensagem sucesso">
            <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="cadastro.php">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['csrf_token'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <h2>Dados do tipo</h2>

        <label for="categoria_id">Categoria</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">Selecione</option>
            <?php foreach ($categorias as $categoria): ?>
                <option
                    value="<?= (int) $categoria['id'] ?>"
                    <?= (
                        ($_POST['categoria_id'] ?? '') ==
                        $categoria['id']
                    ) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars(
                        $categoria['nome'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nome">Nome do instrumento</label>
        <input
            id="nome"
            name="nome"
            maxlength="100"
            value="<?= htmlspecialchars(
                $_POST['nome'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            placeholder="Ex.: Guitarra"
            required
        >

        <div class="linha">
            <div>
                <label for="marca">Marca (opcional)</label>
                <input
                    id="marca"
                    name="marca"
                    maxlength="100"
                    value="<?= htmlspecialchars(
                        $_POST['marca'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>

            <div>
                <label for="modelo">Modelo (opcional)</label>
                <input
                    id="modelo"
                    name="modelo"
                    maxlength="100"
                    value="<?= htmlspecialchars(
                        $_POST['modelo'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
            </div>
        </div>

        <h2>Unidades físicas</h2>

        <label for="quantidade">Quantidade</label>
        <input
            type="number"
            id="quantidade"
            name="quantidade"
            min="1"
            max="100"
            value="<?= htmlspecialchars(
                $_POST['quantidade'] ?? '2',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >

        <label for="estado">Estado de conservação</label>
        <select id="estado" name="estado" required>
            <?php
            $estadoAtual = $_POST['estado'] ?? 'Regular';
            foreach (['Bom', 'Regular', 'Ruim', 'A verificar'] as $opcao):
            ?>
                <option
                    value="<?= $opcao ?>"
                    <?= $estadoAtual === $opcao ? 'selected' : '' ?>
                >
                    <?= $opcao ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div id="lista-cores" class="lista-cores"></div>

        <button type="submit">Cadastrar instrumentos</button>
    </form>
</main>

<script>
const quantidade = document.getElementById('quantidade');
const listaCores = document.getElementById('lista-cores');

// Mantém as cores digitadas caso o servidor retorne um erro.
const coresAnteriores = <?= json_encode(
    array_values(
        array_map(
            fn($c) => is_string($c) ? $c : '',
            is_array($_POST['cores'] ?? null)
                ? $_POST['cores']
                : []
        )
    ),
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
) ?>;

function criarCamposDeCor() {
    const total = Math.max(
        1,
        Math.min(100, Number.parseInt(quantidade.value, 10) || 1)
    );

    const coresAtuais = Array.from(
        listaCores.querySelectorAll('input')
    ).map(input => input.value);

    listaCores.replaceChildren();

    const titulo = document.createElement('h3');
    titulo.textContent = 'Cor de cada unidade';
    listaCores.appendChild(titulo);

    for (let i = 0; i < total; i++) {
        const label = document.createElement('label');
        label.htmlFor = `cor-${i}`;
        label.textContent = `Instrumento ${i + 1}`;

        const input = document.createElement('input');
        input.type = 'text';
        input.id = `cor-${i}`;
        input.name = 'cores[]';
        input.maxLength = 50;
        input.placeholder = 'Ex.: Preta';

        input.value = coresAnteriores[i] ??
            coresAtuais[i] ??
            '';

        listaCores.append(label, input);
    }
}

quantidade.addEventListener('input', criarCamposDeCor);
criarCamposDeCor();
</script>

</body>
</html>
