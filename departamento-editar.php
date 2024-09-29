<?php
require('conexao.php');

$idDepartamento = $_GET['id'] ?? '';

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

    // Atualiza o departamento
    $sql = "UPDATE departamento SET NomeDepartamento = ?, fkCpf = ?, DataInicioGerente = ?, fkidLocalDepartamento = ?
            WHERE NumDepartamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $nomeDepartamento, $fkCpf, $dataInicioGerente, $fkidLocalDepartamento, $idDepartamento);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Departamento atualizado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao atualizar departamento: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Carregar os dados do departamento
$sql = "SELECT * FROM departamento WHERE NumDepartamento = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idDepartamento);
$stmt->execute();
$result = $stmt->get_result();
$departamento = $result->fetch_object();
$stmt->close();

if (!$departamento) {
    die("Departamento não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Departamento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Departamento</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="NomeDepartamento">Nome do Departamento</label>
            <input type="text" class="form-control" id="NomeDepartamento" name="NomeDepartamento" value="<?= htmlspecialchars($departamento->NomeDepartamento, ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label for="fkCpf">CPF do Gerente</label>
            <input type="text" class="form-control" id="fkCpf" name="fkCpf" value="<?= htmlspecialchars($departamento->fkCpf, ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label for="DataInicioGerente">Data de Início do Gerente</label>
            <input type="date" class="form-control" id="DataInicioGerente" name="DataInicioGerente" value="<?= htmlspecialchars($departamento->DataInicioGerente, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="fkidLocalDepartamento">Local do Departamento</label>
            <input type="number" class="form-control" id="fkidLocalDepartamento" name="fkidLocalDepartamento" value="<?= htmlspecialchars($departamento->fkidLocalDepartamento, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Departamento</button>
    </form>
    <a class="btn btn-secondary mt-2" href="home.php?page=departamento-listar">Voltar</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
