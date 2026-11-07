<?php
require '../includes/conexao.php';

$id = $_GET['id'];

// 1. busca a situação atual
$stmt = $pdo->prepare("SELECT concluido FROM checklist WHERE id_checklist = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();
$item = $stmt->fetch();

if ($item) {
    // 2. inverte: S vira N, N vira S
    $novoStatus = $item['concluido'] == 'S' ? 'N' : 'S';

    // 3. grava
    $stmt = $pdo->prepare("UPDATE checklist SET concluido = :concluido WHERE id_checklist = :id");
    $stmt->bindValue(':concluido', $novoStatus);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header('Location: listar.php');
exit;
