<?php
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha

    // Insere o novo usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO funcionario (Cpf, Nome, Senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $cpf, $nome, $senha);

    if ($stmt->execute()) {
        header("Location: login.php"); // Redireciona para a tela de login após o cadastro
        exit;
    } else {
        $erro = "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cadastrar</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastrar</h2>
        <?php if (isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>
        <form action="cadastro.php" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="login.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
