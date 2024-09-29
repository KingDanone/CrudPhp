<?php
require('conexao.php');

// Consulta para listar os projetos, ordenados por idProjeto em ordem crescente
$sql = "SELECT * FROM projeto ORDER BY idProjeto ASC";
$res = $conn->query($sql);

if (!$res) {
    die("Erro na consulta: " . $conn->error);
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
    <title>Lista de Projetos</title>
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
            margin-top: 20px;
        }

        /* Título */
        h2 {
            color: #3CB371;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Tabela */
        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        .table thead {
            background-color: #3CB371;
            color: #ffffff;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Botões */
        .btn-primary {
            background-color: #3CB371;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #00FF00;
        }

        .btn-info {
            background-color: #3CB371;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
        }

        .btn-info:hover {
            background-color: #00FA9A;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Alertas */
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #8FBC8F;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* Margens e Padding */
        .mt-5 {
            margin-top: 3rem !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Projetos</h2>
        <a class="btn btn-primary mb-3" href="projeto-create.php">Novo Projeto</a>

        <?php if ($res->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Local</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['idProjeto']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['Local']); ?></td>
                            <td>
                                <a class="btn btn-sm btn-info" href="projeto-editar.php?id=<?php echo $row['idProjeto']; ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="projeto-delete.php?id=<?php echo $row['idProjeto']; ?>" onclick="return confirm('Tem certeza de que deseja excluir este projeto?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Nenhum projeto cadastrado!</div>
        <?php endif; ?>
    </div>

    <footer class="footer mt-4">
        <p>&copy; 2024 Luis Ricardo</p>
    </footer>
</body>
</html>