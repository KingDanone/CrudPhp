<?php
require('conexao.php');

// Consultar todos os locais de departamento
$sql = "SELECT * FROM local_departamento ORDER BY Nome";
$res = $conn->query($sql);

if (!$res) {
    die("Erro na consulta: " . $conn->error);
}

$qtd = $res->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Locais de Departamento</title>
    <link rel="stylesheet" href="styles.css"> <!-- Verifique o caminho para o CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="card-header">
        <h4>
            Lista de Locais de Departamento
            <a class="btn btn-primary" href="?page=local-create">Novo Local</a>
            <a class="btn btn-secondary" href="home.php?page=departamento-listar">Lista de Departamentos</a> <!-- Novo botão adicionado -->
        </h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($qtd > 0) {
                    while ($row = $res->fetch_object()) {
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row->Nome, ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                        <a href="?page=local-editar&id=<?= urlencode($row->idLocalDepartamento) ?>" class="btn btn-success btn-sm">
    <span class="bi bi-pencil-fill"></span>
    &nbsp;Editar
</a>
                            <form action="local-delete.php" method="POST" class="d-inline">
                                <input type="hidden" name="delete_local" value="<?= htmlspecialchars($row->idLocalDepartamento, ENT_QUOTES, 'UTF-8') ?>">
                                <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" class="btn btn-danger btn-sm">
                                    <span class="bi bi-trash3-fill"></span>&nbsp;Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum local encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a class="btn btn-primary" href="?page=local-create">Novo Local</a>
        <a class="btn btn-secondary" href="home.php?page=departamento-listar">Lista de Departamentos</a> <!-- Novo botão adicionado -->
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>