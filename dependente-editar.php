<?php
require('conexao.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';
$cpf = isset($_GET['cpf']) ? $_GET['cpf'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $datanascimento = $_POST['datanascimento'];
    $sexo = $_POST['sexo'];
    $parentesco = $_POST['parentesco'];

    // Usar uma consulta preparada para evitar SQL Injection
    $stmt = $conn->prepare("UPDATE dependente SET Nome = ?, Datanasc = ?, Sexo = ?, Parentesco = ? WHERE iddependente = ?");
    $stmt->bind_param("ssssi", $nome, $datanascimento, $sexo, $parentesco, $id);

    if ($stmt->execute()) {
        header("Location: home.php?page=funcionario-listar");
        exit;
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    
}

$stmt = $conn->prepare("SELECT * FROM dependente WHERE iddependente = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>   

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dependente</title>
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
        <h2>Editar Dependente</h2>
        <form action="dependente-edit.php?id=<?= urlencode($id) ?>&cpf=<?= urlencode($cpf) ?>" method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($row['Nome']) ?>" placeholder="Nome do Dependente" required>
                <label for="nome">Nome do Dependente</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="datanascimento" name="datanascimento" value="<?= htmlspecialchars($row['Datanasc']) ?>" placeholder="Data de Nascimento" required>
                <label for="datanascimento">Data de Nascimento</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="parentesco" name="parentesco" value="<?= htmlspecialchars($row['Parentesco']) ?>" placeholder="Parentesco" required>
                <label for="parentesco">Parentesco</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="M" <?= $row['Sexo'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= $row['Sexo'] == 'F' ? 'selected' : '' ?>>Feminino</option>
                </select>
                <label for="sexo">Sexo</label>
            </div>
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['iddependente']) ?>">
            <input type="hidden" name="fkCpf" value="<?= htmlspecialchars($cpf) ?>">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="submit" class="btn btn-primary">Voltar</button>
        </form>
    </div>
</div>
</body>
</html>