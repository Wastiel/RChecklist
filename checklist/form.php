<?php
require '../includes/conexao.php';

$id = $_GET['id'] ?? null;

// valores "vazios" para o caso de cadastro novo
$item = [
    'id_checklist' => '',
    'titulo' => '',
    'descricao' => ''
];

// se veio id → é edição: busca no banco
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM checklist WHERE id_checklist = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $item = $stmt->fetch();

    // id inexistente: volta para a lista em vez de dar erro
    if (!$item) {
        header('Location: listar.php');
        exit;
    }
}

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="mb-3"><?= $id ? 'Editar Tarefa' : 'Nova Tarefa' ?></h3>

            <form action="salvar.php" method="POST">

                <input type="hidden" name="id" value="<?= $item['id_checklist'] ?>">

                <div class="mb-3">
                    <label class="form-label">Tarefa</label>
                    <input type="text" name="titulo" class="form-control"
                           value="<?= htmlspecialchars($item['titulo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($item['descricao']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-eeepe">
                    <i class="bi bi-save"></i> Salvar
                </button>
                <a href="listar.php" class="btn btn-secondary">Voltar</a>

            </form>

        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
