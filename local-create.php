<?php
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];

    $stmt = $conn->prepare("INSERT INTO local_departamento (Nome) VALUES (?)");
    $stmt->bind_param("s", $nome);
    
    if ($stmt->execute()) {
        header("Location: home.php?page=local-listar");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erro ao criar o local: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <title>Criar Local</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Criar Novo Local</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a class="btn btn-secondary" href="home.php?page=local-listar">Cancelar</a>
        </form>
    </div>
</body>
</html>