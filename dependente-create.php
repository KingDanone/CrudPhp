<?php
require('conexao.php');

// Obtém o CPF do funcionário da URL
$cpf = isset($_GET['cpf']) ? htmlspecialchars($_GET['cpf']) : '';

// Verifique se o CPF existe na tabela funcionario
$checkStmt = $conn->prepare("SELECT COUNT(*) FROM funcionario WHERE Cpf = ?");
$checkStmt->bind_param("s", $cpf);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();
$checkStmt->close();

if ($count === 0) {
    die("Erro: CPF não encontrado na tabela de funcionários.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $datanascimento = $_POST['datanascimento'];
    $sexo = $_POST['sexo'];
    $parentesco = $_POST['parentesco'];

    // Usa uma consulta preparada para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO dependente (Nome, Datanasc, Sexo, Parentesco, fkCpf) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $datanascimento, $sexo, $parentesco, $cpf);

    if ($stmt->execute()) {
        header("Location: home.php?page=funcionario-listar");
        exit;
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Dependente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating label {
            color: #6c757d;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-shadow: none;
            transition: border-color 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .form-select {
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
            font-size: 16px;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
            text-decoration: none;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            background-color: #ffffff;
            border: 1px solid #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .back-link:hover {
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
        }
    </style>
    </head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Cadastro de Dependente</h2>
        <form action="dependente-create.php?cpf=<?= htmlspecialchars($cpf) ?>" method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Dependente" required>
                <label for="nome">Nome do Dependente</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="datanascimento" name="datanascimento" placeholder="Data de Nascimento" required>
                <label for="datanascimento">Data de Nascimento</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="parentesco" name="parentesco" placeholder="Parentesco" required>
                <label for="parentesco">Parentesco</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="" selected>Selecione o Sexo</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
                <label for="sexo">Sexo</label>
            </div>
            <input type="hidden" name="fkCpf" value="<?= htmlspecialchars($cpf) ?>">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="home.php?page=funcionario-listar" class="btn btn-primary back-link">Voltar</a>
        </form>
    </div>
</div>
</body>
</html>