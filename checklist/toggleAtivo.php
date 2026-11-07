<?php
require '../includes/conexao.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT ativo FROM checklist WHERE id_checklist = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();
$item = $stmt->fetch();

if ($item) {
    $novoStatus = $item['ativo'] == 'S' ? 'N' : 'S';

    $stmt = $pdo->prepare("UPDATE checklist SET ativo = :ativo WHERE id_checklist = :id");
    $stmt->bindValue(':ativo', $novoStatus);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header('Location: listar.php');
exit;
