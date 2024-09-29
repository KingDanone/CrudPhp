<?php
require('conexao.php');

// Verifica se o parâmetro 'id' está presente na URL
if (isset($_GET['id'])) {
    $idProjeto = intval($_GET['id']);

    // Inicia a transação
    $conn->begin_transaction();

    try {
        // Primeiro, exclua os registros na tabela 'trabalha_em' que referenciam o projeto
        $stmt = $conn->prepare("DELETE FROM trabalha_em WHERE fkIdProjeto = ?");
        $stmt->bind_param("i", $idProjeto);
        $stmt->execute();
        $stmt->close();

        // Excluir outros registros associados ao projeto (adicione aqui outras tabelas relacionadas)
        // Exemplo: Se houver uma tabela 'tabela_exemplo' que também tem uma FK para 'projeto'
        // $stmt = $conn->prepare("DELETE FROM tabela_exemplo WHERE fkIdProjeto = ?");
        // $stmt->bind_param("i", $idProjeto);
        // $stmt->execute();
        // $stmt->close();

        // Agora, exclua o projeto
        $stmt = $conn->prepare("DELETE FROM projeto WHERE idProjeto = ?");
        $stmt->bind_param("i", $idProjeto);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Commit a transação
            $conn->commit();
            header("Location: home.php?page=projeto-listar");
            exit;
        } else {
            throw new Exception("Nenhum projeto encontrado para excluir.");
        }

    } catch (mysqli_sql_exception $e) {
        // Rollback a transação em caso de erro
        $conn->rollback();
        echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID do projeto não fornecido.</div>";
    echo "<a href='home.php?page=projeto-listar' class='btn btn-primary'>Voltar</a>";
}
?>