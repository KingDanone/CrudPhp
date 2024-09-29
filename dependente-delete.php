<?php
require('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_dependente'])) {
    // Sanitizar e validar os dados recebidos
    $iddependente = isset($_POST['iddependente']) ? intval($_POST['iddependente']) : 0;
    $cpf = isset($_POST['fkCpf']) ? htmlspecialchars($_POST['fkCpf']) : '';

    if ($iddependente > 0) {
        // Usar uma consulta preparada para evitar SQL Injection
        $sql = "DELETE FROM dependente WHERE iddependente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $iddependente);

        if ($stmt->execute()) {
            // Redirecionar para a lista de funcionario
            header("Location: home.php?page=funcionario-listar");
            exit;
        } else {
            echo "<script>alert('Erro ao excluir o dependente: " . $stmt->error . "');</script>";
            header("Location: home.php?page=dependente-listar&cpf=" . urlencode($cpf));
            exit;
        }
    } else {
        echo "<script>alert('ID do dependente inválido.');</script>";
        header("Location: home.php?page=dependente-listar&cpf=" . urlencode($cpf));
        exit;
    }
}
?>