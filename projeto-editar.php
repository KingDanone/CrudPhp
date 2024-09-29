<?php
require('conexao.php');

// Verifica se o ID do projeto está presente na URL
if (!isset($_GET['id'])) {
    die("Erro: ID do projeto não especificado.");
}

$idProjeto = intval($_GET['id']);

// Obtém os dados do projeto para preenchimento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM projeto WHERE idProjeto = ?");
    $stmt->bind_param("i", $idProjeto);
    $stmt->execute();
    $result = $stmt->get_result();
    $projeto = $result->fetch_assoc();
    $stmt->close();

    if (!$projeto) {
        die("Erro: Projeto não encontrado.");
    }
}

// Atualiza o projeto com os dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $local = $_POST['local'];

    $stmt = $conn->prepare("UPDATE projeto SET Nome = ?, Local = ? WHERE idProjeto = ?");
    $stmt->bind_param("ssi", $nome, $local, $idProjeto);

    if ($stmt->execute()) {
        header("Location: home.php?page=projeto-listar");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <title>Editar Projeto</title>
    <style>
        /* styles.css */

/* Geral */
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    color: #212529;
    margin: 0;
    padding: 0;
}

/* Container */
.container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Título */
h2 {
    color: #00FF7F;
    font-size: 2rem;
    margin-bottom: 20px;
}

/* Formulário */
form {
    max-width: 600px;
    margin: auto;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control {
    border-radius: 4px;
    border: 1px solid #ced4da;
    padding: 10px;
}

.form-control:focus {
    border-color: #00FF7F;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

/* Botões */
.btn-primary {
    background-color: #00FA9A;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
}

.btn-primary:hover {
    background-color: #00FF00;
}

.btn-secondary {
    background-color: #6c757d;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Alertas */
.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    border-radius: 4px;
    padding: 10px;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Projeto</h2>
        <form action="projeto-editar.php?id=<?php echo htmlspecialchars($idProjeto); ?>" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($projeto['Nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="local" class="form-label">Local</label>
                <input type="text" class="form-control" id="local" name="local" value="<?php echo htmlspecialchars($projeto['Local']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="home.php?page=projeto-listar" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <!-- Rodapé -->
    <footer>
      <div id="copy-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                      Desenvolvido pelo Prof. Edilson Lima &copy; 2024
                    </p>
                </div>
            </div>
        </div>
      </div>
    </footer>
</body>
</html>