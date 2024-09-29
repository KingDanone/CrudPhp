<?php
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeDepartamento = $_POST['NomeDepartamento'];
    $fkCpf = $_POST['fkCpf'];
    $dataInicioGerente = $_POST['DataInicioGerente'];
    $fkidLocalDepartamento = $_POST['fkidLocalDepartamento'];

    // Protege contra SQL Injection
    $nomeDepartamento = $conn->real_escape_string($nomeDepartamento);
    $fkCpf = $conn->real_escape_string($fkCpf);
    $dataInicioGerente = $conn->real_escape_string($dataInicioGerente);
    $fkidLocalDepartamento = (int)$fkidLocalDepartamento;

    // Insere o novo departamento
    $sql = "INSERT INTO departamento (NomeDepartamento, fkCpf, DataInicioGerente, fkidLocalDepartamento) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $nomeDepartamento, $fkCpf, $dataInicioGerente, $fkidLocalDepartamento);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Departamento criado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao criar departamento: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Departamento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Criar Novo Departamento</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="NomeDepartamento">Nome do Departamento</label>
            <input type="text" class="form-control" id="NomeDepartamento" name="NomeDepartamento" required>
        </div>
        <div class="form-group">
            <label for="fkCpf">CPF do Gerente</label>
            <input type="text" class="form-control" id="fkCpf" name="fkCpf" required>
        </div>
        <div class="form-group">
            <label for="DataInicioGerente">Data de Início do Gerente</label>
            <input type="date" class="form-control" id="DataInicioGerente" name="DataInicioGerente">
        </div>
        <div class="form-group">
            <label for="fkidLocalDepartamento">Local do Departamento</label>
            <input type="number" class="form-control" id="fkidLocalDepartamento" name="fkidLocalDepartamento">
        </div>
        <button type="submit" class="btn btn-primary">Criar Departamento</button>
    </form>
    <a class="btn btn-secondary mt-2" href="home.php?page=departamento-listar">Voltar</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>