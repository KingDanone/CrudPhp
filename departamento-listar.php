<?php
require('conexao.php');

// Consultar todos os departamentos
$sql = "SELECT * FROM departamento ORDER BY NomeDepartamento"; // Ajustado para o nome da coluna correto
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
    <title>Lista de Departamentos</title>
    <link rel="stylesheet" href="styles.css"> <!-- Verifique o caminho para o CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="card-header">
        <h4>
            Lista de Departamentos
            <a class="btn btn-primary" href="?page=departamento-create">Novo Departamento</a>
            <a class="btn btn-secondary" href="home.php?page=local-listar">Lista de Locais</a> <!-- Botão de acesso à lista de locais -->
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
                        <td><?= htmlspecialchars($row->NomeDepartamento, ENT_QUOTES, 'UTF-8') ?></td> <!-- Ajustado para o nome da coluna correto -->
                        <td>
                            <a href="?page=departamento-editar&id=<?= urlencode($row->NumDepartamento) ?>" class="btn btn-success btn-sm">
                                <span class="bi bi-pencil-fill"></span>
                                &nbsp;Editar
                            </a>
                            <form action="departamento-delete.php" method="POST" class="d-inline">
                                <input type="hidden" name="delete_departamento" value="<?= htmlspecialchars($row->NumDepartamento, ENT_QUOTES, 'UTF-8') ?>">
                                <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" class="btn btn-danger btn-sm">
                                    <span class="bi bi-trash3-fill"></span>&nbsp;Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum departamento encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a class="btn btn-primary" href="?page=departamento-create">Novo Departamento</a>
        <a class="btn btn-secondary" href="home.php?page=local-listar">Lista de Locais</a> <!-- Botão de acesso à lista de locais -->
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>