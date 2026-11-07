<?php
require '../includes/conexao.php';

$sql = "
    SELECT id_checklist, titulo, descricao, concluido
    FROM checklist
    ORDER BY concluido, titulo
";

$itens = $pdo->query($sql)->fetchAll();

require '../includes/header.php';
require '../includes/menu.php';
?>

<div class="container mt-4">

    <div class="d-flex mb-3">
        <h3>Checklist</h3>
        <button type="button" class="btn btn-eeepe ms-auto"
                data-bs-toggle="modal" data-bs-target="#modalForm"
                data-id="">
            <i class="bi bi-plus-circle"></i> Nova Tarefa
        </button>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th style="width: 50px;"></th>
                <th>Tarefa</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($itens as $item): ?>
            <tr>
                <!-- Checkbox: ao clicar, vai para toggleConcluido.php -->
                <td>
                    <input type="checkbox" class="form-check-input"
                           <?= $item['concluido'] == 'S' ? 'checked' : '' ?>
                           onchange="window.location='toggleConcluido.php?id=<?= $item['id_checklist'] ?>'">
                </td>

                <!-- Título: clicar abre a modal com a descrição -->
                <td>
                    <a href="#" class="text-decoration-none"
                       data-bs-toggle="modal"
                       data-bs-target="#modal<?= $item['id_checklist'] ?>">
                        <?php if($item['concluido'] == 'S'): ?>
                            <s class="text-muted"><?= htmlspecialchars($item['titulo']) ?></s>
                        <?php else: ?>
                            <?= htmlspecialchars($item['titulo']) ?>
                        <?php endif; ?>
                    </a>
                </td>

                <td>
                    <?php if($item['concluido'] == 'S'): ?>
                        <span class="badge badge-concluido">Concluída</span>
                    <?php else: ?>
                        <span class="badge badge-aberto">Pendente</span>
                    <?php endif; ?>
                </td>

                <td>
                    <button type="button" class="btn btn-sm btn-eeepe-warning"
                            data-bs-toggle="modal" data-bs-target="#modalForm"
                            data-id="<?= $item['id_checklist'] ?>"
                            data-titulo="<?= htmlspecialchars($item['titulo'], ENT_QUOTES) ?>"
                            data-descricao="<?= htmlspecialchars($item['descricao'], ENT_QUOTES) ?>">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>

                    <form action="excluir.php" method="POST" class="d-inline"
                          onsubmit="return confirm('Excluir esta tarefa?');">
                        <input type="hidden" name="id" value="<?= $item['id_checklist'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Excluir
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<!-- Modal única: serve para CRIAR e EDITAR (quem decide é o campo id) -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="salvar.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormTitulo">Nova Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- id vazio = INSERT | id preenchido = UPDATE (veja salvar.php) -->
                    <input type="hidden" name="id" id="campoId">

                    <div class="mb-3">
                        <label class="form-label">Tarefa</label>
                        <input type="text" name="titulo" id="campoTitulo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" id="campoDescricao" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-eeepe">
                        <i class="bi bi-save"></i> Salvar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Uma modal por tarefa. O id (modal5, modal6...) precisa ser único -->
<?php foreach($itens as $item): ?>
    <div class="modal fade" id="modal<?= $item['id_checklist'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><?= htmlspecialchars($item['titulo']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <?php if(empty($item['descricao'])): ?>
                        <span class="text-muted">Sem descrição.</span>
                    <?php else: ?>
                        <?= nl2br(htmlspecialchars($item['descricao'])) ?>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
// Quando a modal abre, descobre qual botão a chamou (Nova ou Editar)
// e preenche os campos com os data-* desse botão.
document.getElementById('modalForm').addEventListener('show.bs.modal', function (event) {
    var botao = event.relatedTarget;
    var id = botao.dataset.id || '';

    document.getElementById('campoId').value = id;
    document.getElementById('campoTitulo').value = botao.dataset.titulo || '';
    document.getElementById('campoDescricao').value = botao.dataset.descricao || '';
    document.getElementById('modalFormTitulo').textContent = id ? 'Editar Tarefa' : 'Nova Tarefa';
});
</script>

<?php require '../includes/footer.php'; ?>