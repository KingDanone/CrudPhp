<?php
require('conexao.php');

// Recebe e sanitiza o CPF
$cpf = isset($_REQUEST["id"]) ? htmlspecialchars($_REQUEST["id"]) : '';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Dependentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .btn {
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
            border-radius: .2rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .table th,
        .table td {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Listagem de Dependentes</h2>
        <a class="btn btn-primary mb-3" href="dependente-create.php?cpf=<?= htmlspecialchars($cpf) ?>">Novo Dependente</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Sexo</th>
                    <th>Data de Nascimento</th>
                    <th>Parentesco</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Preparar a consulta para evitar SQL Injection
            $sql = "SELECT iddependente, Nome, Sexo, Datanasc, Parentesco 
                    FROM dependente 
                    WHERE fkCpf = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param('s', $cpf);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($row = $res->fetch_object()) {
        ?>
                    <tr>
                        <td><?= htmlspecialchars($row->Nome) ?></td>
                        <td><?= htmlspecialchars($row->Sexo) ?></td>
                        <td><?= date('d/m/Y', strtotime($row->Datanasc)) ?></td>
                        <td><?= htmlspecialchars($row->Parentesco) ?></td>
                        <td>
                            <a href="dependente-editar.php?id=<?= urlencode($row->iddependente) ?>&cpf=<?= urlencode($cpf) ?>" class="btn btn-success btn-sm">Editar</a>
                            <form action="dependente-delete.php" method="POST" class="d-inline">
                                <input type="hidden" name="iddependente" value="<?= htmlspecialchars($row->iddependente) ?>">
                                <input type="hidden" name="fkCpf" value="<?= htmlspecialchars($cpf) ?>">
                                <button type="submit" name="delete_dependente" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php
                }
            } else {
                echo '<tr><td colspan="5">Nenhum dependente encontrado.</td></tr>';
            }
            ?>
            </tbody>
        </table>
        <a href="home.php?page=funcionario-listar" class="btn btn-secondary mt-3">Voltar para Listagem de Funcionários</a>
    </div>
</body>
</html>