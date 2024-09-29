<?php
require('conexao.php');

// Verifica se há uma solicitação de exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_departamento'])) {
    $numDepartamento = $_POST['delete_departamento'];

    // Protege contra SQL Injection
    $numDepartamento = (int)$numDepartamento;

    // Inicia a transação
    $conn->begin_transaction();

    try {
        // Primeiro, exclua os registros relacionados
        // (Caso tenha restrições de integridade referencial ou outras tabelas relacionadas)

        // Agora, exclua o departamento
        $sql = "DELETE FROM departamento WHERE NumDepartamento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $numDepartamento);
        $stmt->execute();
        $stmt->close();

        // Commit a transação
        $conn->commit();

        echo "<div class='alert alert-success'>Departamento excluído com sucesso!</div>";
    } catch (mysqli_sql_exception $e) {
        // Rollback a transação em caso de erro
        $conn->rollback();
        echo "<div class='alert alert-danger'>Erro ao excluir departamento: " . $e->getMessage() . "</div>";
    }
}

// Redireciona de volta para a lista de departamentos
header('Location: home.php?page=departamento-listar');
exit();
?>