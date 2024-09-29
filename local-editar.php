<?php
require('conexao.php');

// Verifica se o ID foi fornecido
if (isset($_GET['id'])) {
    $idLocalDepartamento = $_GET['id'];

    // Protege contra SQL Injection
    $idLocalDepartamento = $conn->real_escape_string($idLocalDepartamento);

    // Consulta o local de departamento
    $sql = "SELECT * FROM local_departamento WHERE idLocalDepartamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idLocalDepartamento);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $local = $res->fetch_assoc();
    } else {
        die("Local não encontrado.");
    }

    $stmt->close();
} else {
    die("ID não fornecido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza o local de departamento
    $nome = $_POST['nome'];

    $sql = "UPDATE local_departamento SET Nome = ? WHERE idLocalDepartamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $idLocalDepartamento);

    if ($stmt->execute()) {
        header("Location: home.php?page=local-listar");
        exit();
    } else {
        die("Erro ao atualizar o local.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Local de Departamento</title>
    <link rel="stylesheet" href="styles.css"> <!-- Verifique o caminho para o CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Local de Departamento</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($local['Nome'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="home.php?page=local-listar" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
</html>