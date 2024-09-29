<?php
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_funcionario'])) {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $datanascimento = $_POST['datanascimento'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];
    $salario = $_POST['salario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Validar o CPF
    $cpf = preg_replace('/\D/', '', $cpf); // Remove caracteres não numéricos
    if (strlen($cpf) != 11) {
        echo "<script>alert('O CPF deve ter 11 dígitos.');</script>";
        exit;
    }

    // Validar a confirmação da senha
    if ($senha !== $confirma_senha) {
        echo "<script>alert('As senhas não coincidem.');</script>";
        exit;
    }

    // Criptografar a senha
    $senha = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar se o CPF já existe
    $sql = "SELECT * FROM funcionario WHERE Cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('CPF já cadastrado.');</script>";
    } else {
        // Inserir novo funcionário
        $sql = "INSERT INTO funcionario (Cpf, Nome, DataNascimento, Endereco, Sexo, Salario, Email, Senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssss', $cpf, $nome, $datanascimento, $endereco, $sexo, $salario, $email, $senha);

        if ($stmt->execute()) {
            header('Location: home.php?page=funcionario-listar');
            exit;
        } else {
            echo "<script>alert('Erro ao cadastrar o funcionário: " . $stmt->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="form-container">
        <h2 class="mb-4">Cadastro de Funcionário</h2>
        <form action="" method="POST" onsubmit="return validatePassword()">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF" required>
                <label for="cpf">Digite seu CPF</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" required>
                <label for="nome">Digite seu nome</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="datanascimento" name="datanascimento" required>
                <label for="datanascimento">Data de nascimento</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite seu endereço" required>
                <label for="endereco">Digite seu endereço</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="" disabled selected>Selecione sexo</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
                <label for="sexo">Sexo</label>
            </div>
            <div class="form-group mb-3">
                <label for="salario">Salário</label>
                <input type="number" class="form-control" id="salario" name="salario" placeholder="Digite seu salário" required>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
                <label for="email">Digite seu email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                <label for="senha">Digite sua senha</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" placeholder="Confirme sua senha" required>
                <label for="confirma_senha">Confirme sua senha</label>
            </div>
            <button type="submit" name="create_funcionario" class="btn btn-primary">Salvar</button>
            <a href="?page=funcionario-listar" class="btn btn-secondary">Voltar para a lista</a>
        </form>
    </div>
</div>
<script>
    function validatePassword() {
        var senha = document.getElementById('senha').value;
        var confirmaSenha = document.getElementById('confirma_senha').value;
        if (senha !== confirmaSenha) {
            alert('As senhas não coincidem.');
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>