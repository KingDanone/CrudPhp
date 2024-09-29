<?php
require('conexao.php');

$idProjeto = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEditing = $idProjeto > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $local = $_POST['local'];
    $fkNumDepartamento = $_POST['fkNumDepartamento'];

    if ($isEditing) {
        // Atualização de um projeto existente
        $sql = "UPDATE projeto SET Nome = ?, Local = ?, fkNumDepartamento = ? WHERE idProjeto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $nome, $local, $fkNumDepartamento, $idProjeto);
    } else {
        // Criação de um novo projeto
        $sql = "INSERT INTO projeto (Nome, Local, fkNumDepartamento) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nome, $local, $fkNumDepartamento);
    }

    if ($stmt->execute()) {
        header("Location: home.php?page=projeto-listar");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erro: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

if ($isEditing) {
    $sql = "SELECT * FROM projeto WHERE idProjeto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProjeto);
    $stmt->execute();
    $result = $stmt->get_result();
    $projeto = $result->fetch_object();
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
    <title><?php echo $isEditing ? 'Editar Projeto' : 'Novo Projeto'; ?></title>
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

/* Container do Formulário */
#form-container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

/* Título */
h4 {
    color: #00FF7F;
    font-size: 1.5rem;
    margin-bottom: 20px;
}

/* Imagem */
.img-fluid {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
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
    border-color: #00FA9A;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

/* Select */
.form-select {
    border-radius: 4px;
    border: 1px solid #ced4da;
}

/* Botões */
.btn-primary {
    background-color: #00FF7F;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    margin-right: 10px;
}

.btn-primary:hover {
    background-color: #00FA9A;
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

/* Footer */
.footer {
    text-align: center;
    padding: 10px;
    background-color: #ffffff;
    border-top: 1px solid #ced4da;
    margin-top: 20px;
}

    </style>
</head>
<body>
    <div class="container col-11 col-md-9" id="form-container">
        <div class="row align-items-center gx-5">
            <div class="col-md-6 order-md-2">
                <h4><?php echo $isEditing ? 'Editar Projeto' : 'Novo Projeto'; ?></h4>
                <form action="projeto-create.php<?php echo $isEditing ? '?id=' . $idProjeto : ''; ?>" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $isEditing ? htmlspecialchars($projeto->Nome) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="local" class="form-label">Local</label>
                        <input type="text" class="form-control" id="local" name="local" value="<?php echo $isEditing ? htmlspecialchars($projeto->Local) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fkNumDepartamento" class="form-label">Departamento</label>
                        <select id="fkNumDepartamento" name="fkNumDepartamento" class="form-select" required>
                            <?php
                            $departamentoResult = $conn->query("SELECT NumDepartamento, NomeDepartamento FROM departamento");
                            while ($departamento = $departamentoResult->fetch_object()) {
                                $selected = $isEditing && $departamento->NumDepartamento == $projeto->fkNumDepartamento ? 'selected' : '';
                                echo "<option value='{$departamento->NumDepartamento}' $selected>{$departamento->NomeDepartamento}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $isEditing ? 'Atualizar Projeto' : 'Salvar Projeto'; ?></button>
                    <a class="btn btn-secondary" href="home.php?page=projeto-listar">Cancelar</a>
                </form>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="/empresa/img/empresa.jpg" alt="Projeto" class="img-fluid">
            </div>
        </div>
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