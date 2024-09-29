<?php
require('conexao.php');

// Verifica se há uma solicitação de exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_funcionario'])) {
    $cpf = $_POST['delete_funcionario'];

    // Protege contra SQL Injection
    $cpf = $conn->real_escape_string($cpf);

    // Verifica se o CPF está no formato correto
    if (preg_match('/^\d{11}$/', $cpf)) {
        // Verifica se o funcionário tem dependentes
        $sql = "SELECT COUNT(*) AS qtd FROM dependente WHERE fkCpf = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $stmt->bind_result($qtd_dependentes);
        $stmt->fetch();
        $stmt->close();

        if ($qtd_dependentes > 0) {
            // Exibe uma mensagem informando que o funcionário não pode ser excluído
            echo "<div class='alert alert-warning'>O funcionário não pode ser excluído porque possui dependentes associados.</div>";
        } else {
            // Inicia a transação
            $conn->begin_transaction();

            try {
                // Primeiro, exclua os registros na tabela 'trabalha_em'
                $sql = "DELETE FROM trabalha_em WHERE fkCpf = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $cpf);
                $stmt->execute();
                $stmt->close();

                // Agora, exclua o funcionário
                $sql = "DELETE FROM funcionario WHERE Cpf = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $cpf);
                $stmt->execute();
                $stmt->close();

                // Commit a transação
                $conn->commit();

                echo "<div class='alert alert-success'>Funcionário excluído com sucesso!</div>";
            } catch (mysqli_sql_exception $e) {
                // Rollback a transação em caso de erro
                $conn->rollback();
                echo "<div class='alert alert-danger'>Erro ao excluir o funcionário: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>CPF inválido.</div>";
    }
}

// Consultar todos os funcionários
$sql = "SELECT * FROM funcionario ORDER BY Nome";
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
    <title>Lista de Funcionários</title>
    <link rel="stylesheet" href="styles.css"> <!-- Verifique o caminho para o CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="card-header">
        <h4>
            Lista de Funcionários
            <a class="btn btn-primary" href="?page=funcionario-create">Novo Funcionário</a>
        </h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data Nascimento</th>
                    <th>Sexo</th>
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
                        <td><?= date('d/m/Y', strtotime($row->DataNascimento)) ?></td>
                        <td><?= htmlspecialchars($row->Sexo, ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="?page=dependente-listar&id=<?= urlencode($row->Cpf) ?>" class="btn btn-secondary btn-sm">
                                <span class="bi bi-eye-fill"></span>
                                &nbsp;Dependente
                            </a>
                            <a href="?page=funcionario-editar&id=<?= urlencode($row->Cpf) ?>" class="btn btn-success btn-sm">
                                <span class="bi bi-pencil-fill"></span>
                                &nbsp;Editar
                            </a>
                            <form action="" method="POST" class="d-inline">
                                <input type="hidden" name="delete_funcionario" value="<?= htmlspecialchars($row->Cpf, ENT_QUOTES, 'UTF-8') ?>">
                                <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" class="btn btn-danger btn-sm">
                                    <span class="bi bi-trash3-fill"></span>&nbsp;Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum funcionário encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a class="btn btn-primary" href="?page=funcionario-create">Novo Funcionário</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>