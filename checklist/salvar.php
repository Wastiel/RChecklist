<?php
require '../includes/conexao.php';

$id = $_POST['id'];
$titulo = trim($_POST['titulo'] ?? '');

// validação no servidor (o "required" do HTML pode ser burlado)
if ($titulo === '') {
    header('Location: listar.php');
    exit;
}

if (empty($id)) {
    // ID vazio → cadastro novo → INSERT
    $sql = "INSERT INTO checklist (titulo, descricao, concluido)
            VALUES (:titulo, :descricao, 'N')";
} else {
    // ID preenchido → edição → UPDATE
    $sql = "UPDATE checklist
            SET titulo = :titulo, descricao = :descricao
            WHERE id_checklist = :id";
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':titulo', $titulo);
$stmt->bindValue(':descricao', $_POST['descricao']);

if (!empty($id)) {
    $stmt->bindValue(':id', $id);
}

$stmt->execute();

header('Location: listar.php');
exit;