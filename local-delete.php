<?php
require('conexao.php');

// Verifica se o parâmetro 'id' está presente na URL
if (isset($_GET['id'])) {
    $idLocalDepartamento = intval($_GET['id']);

    // Verifica se o local pode ser excluído
    $sql = "SELECT COUNT(*) AS qtd FROM departamento WHERE fkIdLocalDepartamento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idLocalDepartamento);
    $stmt->execute();
    $stmt->bind_result($qtd_registros);
    $stmt->fetch();
    $stmt->close();

    if ($qtd_registros > 0) {
        // Exibe uma mensagem informando que o local não pode ser excluído
        echo "<div class='alert alert-warning'>O local não pode ser excluído porque possui registros associados.</div>";
        echo "<a href='local-listar.php' class='btn btn-primary'>Voltar</a>";
    } else {
        // Inicia a transação
        $conn->begin_transaction();

        try {
            // Exclui o local
            $sql = "DELETE FROM local_departamento WHERE idLocalDepartamento = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idLocalDepartamento);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Commit a transação
                $conn->commit();
                header("Location: home.php?page=local-listar");
                exit;
            } else {
                throw new Exception("Nenhum local encontrado para excluir.");
            }

        } catch (mysqli_sql_exception $e) {
            // Rollback a transação em caso de erro
            $conn->rollback();
            echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
        }
    }
} else {
    die("ID não especificado.");
}

$conn->close();
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
    <title>Excluir Local</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Excluir Local</h2>
        <p>Tem certeza de que deseja excluir este local? Essa ação não pode ser desfeita.</p>
        <a href="local-delete.php?id=<?php echo $idLocalDepartamento; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza de que deseja excluir este local?')">Excluir</a>
        <a href="home.php?page=local-listar" class="btn btn-secondary">Cancelar</a>
    </div>
</body>
</html>