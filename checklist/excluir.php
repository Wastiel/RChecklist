<?php
require '../includes/conexao.php';

// só aceita POST: excluir é uma ação que altera dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM checklist WHERE id_checklist = :id");
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
}

header('Location: listar.php');
exit;