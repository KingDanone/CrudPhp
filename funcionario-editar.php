<?php
require('conexao.php');

if (isset($_GET['id'])) {
    $cpf = $_GET['id'];

    // Sanitizar e validar o CPF
    $cpf = htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8');

    // Buscar dados do funcionário
    $sql = "SELECT * FROM funcionario WHERE Cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('Funcionário não encontrado.');</script>";
        header('Location: home.php?page=funcionario-listar');
        exit;
    }

    $row = $result->fetch_assoc();
} else {
    echo "<script>alert('ID do funcionário não fornecido.');</script>";
    header('Location: home.php?page=funcionario-listar');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_funcionario'])) {
    $nome = $_POST['nome'];
    $datanascimento = $_POST['datanascimento'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];
    $salario = $_POST['salario'];
    $email = $_POST['email'];
    $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : ''; // Senha opcional

    // Atualizar dados do funcionário
    // Se a senha não for fornecida, não a atualize
    $sql = "UPDATE funcionario SET Nome = ?, DataNascimento = ?, Endereco = ?, Sexo = ?, Salario = ?, Email = ?" . (!empty($senha) ? ", Senha = ?" : "") . " WHERE Cpf = ?";
    $stmt = $conn->prepare($sql);

    if (!empty($senha)) {
        $stmt->bind_param('ssssssss', $nome, $datanascimento, $endereco, $sexo, $salario, $email, $senha, $cpf);
    } else {
        $stmt->bind_param('sssssss', $nome, $datanascimento, $endereco, $sexo, $salario, $email, $cpf);
    }

    if ($stmt->execute()) {
        header('Location: home.php?page=funcionario-listar');
        exit;
    } else {
        echo "<script>alert('Erro ao atualizar o funcionário: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Inclua seu CSS personalizado -->
    <style>
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 1.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
        }

        .form-floating input, .form-floating select {
            border-radius: 0.375rem;
        }

        .form-floating label {
            padding: 0.5rem;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-custom {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="form-container">
        <h2 class="mb-4">Atualize o Cadastro do Funcionário</h2>
        <form action="" method="POST" onsubmit="return validatePassword()">
            <!-- Campo CPF (não editável) -->
            <div class="form-floating mb-3">
                <input type="hidden" name="cpf" value="<?= htmlspecialchars($row['Cpf'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($row['Cpf'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                <label for="cpf">CPF</label>
            </div>
            <!-- Campo Nome -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($row['Nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="nome">Nome</label>
            </div>
            <!-- Campo Data de Nascimento -->
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="datanascimento" name="datanascimento" value="<?= htmlspecialchars($row['DataNascimento'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="datanascimento">Data de Nascimento</label>
            </div>
            <!-- Campo Endereço -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($row['Endereco'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="endereco">Endereço</label>
            </div>
            <!-- Campo Sexo -->
            <div class="form-floating mb-3">
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="" disabled selected>Selecione sexo</option>
                    <option value="M" <?= $row['Sexo'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= $row['Sexo'] == 'F' ? 'selected' : '' ?>>Feminino</option>
                </select>
                <label for="sexo">Sexo</label>
            </div>
            <!-- Campo Salário -->
            <div class="form-group mb-3">
                <label for="salario">Salário</label>
                <input type="number" class="form-control" id="salario" name="salario" value="<?= htmlspecialchars($row['Salario'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <!-- Campo Email -->
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($row['Email'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="email">Email</label>
            </div>
            <!-- Campo Nova Senha -->
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Nova senha (deixe em branco para manter a atual)">
                <label for="senha">Nova Senha</label>
            </div>
            <input type="submit" name="edit_funcionario" class="btn btn-primary" value="Salvar">
            <a href="?page=funcionario-listar" class="btn btn-secondary btn-custom">Voltar para a lista</a>
        </form>
    </div>
</div>
<script>
    function validatePassword() {
        var senha = document.getElementById('senha').value;
        if (senha && senha.length < 6) { // Verifica se a senha tem pelo menos 6 caracteres
            alert('A senha deve ter pelo menos 6 caracteres.');
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>