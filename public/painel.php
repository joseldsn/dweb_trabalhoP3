<?php require_once __DIR__.'/_header.php'; require_once __DIR__.'/../includes/db.php'; require_login(); ?>
<?php $tab = $_GET['tab'] ?? 'itens'; ?>

<ul class="nav nav-pills mb-3" role="tablist">
  <?php if (is_admin()): ?>
  <li class="nav-item"><a class="nav-link <?= ($tab==='usuarios')?'active':'' ?>" href="painel.php?tab=usuarios"><i class="bi bi-people me-1"></i> Usuários</a></li>
<?php endif; ?>
</ul>

<?php if ($tab==='itens'): ?>
  <?php
    $editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editItem = null;
if ($editId>0) {
  $st = mysqli_prepare($conn, "SELECT * FROM items WHERE id=? LIMIT 1");
  mysqli_stmt_bind_param($st, "i", $editId);
  mysqli_stmt_execute($st);
  $r = mysqli_stmt_get_result($st);
  $editItem = $r ? mysqli_fetch_assoc($r) : null;
  if (!$editItem) { $editId = 0; }
  if ($editItem && !is_admin() && (int)$editItem['user_id'] !== (int)$_SESSION['user']['id']) {
    $editId = 0; $editItem = null;
  }
}
  ?>
  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card rounded-xl shadow-soft"><div class="card-body">
        <h5 class="card-title mb-3"><?= $editItem?'Editar ponto de doação':'Cadastrar ponto de doação' ?></h5>
        <form action="<?= $editItem?'../actions/item_update.php':'../actions/item_create.php' ?>" method="post" class="needs-validation" novalidate>
          <?php if ($editItem): ?><input type="hidden" name="id" value="<?= (int)$editItem['id'] ?>"><?php endif; ?>
          <div class="mb-2">
            <label class="form-label">Nome do local</label>
            <input class="form-control" name="nome" required minlength="2" value="<?= htmlspecialchars($editItem['nome'] ?? '') ?>">
          </div>
          <div class="mb-2">
            <label class="form-label">Endereço</label>
            <input class="form-control" name="endereco" required minlength="3" value="<?= htmlspecialchars($editItem['endereco'] ?? '') ?>">
          </div>
          <div class="mb-2">
            <label class="form-label">Horário de funcionamento</label>
            <input class="form-control" name="horario" required minlength="3" placeholder="Ex.: Seg–Sex 8h–17h" value="<?= htmlspecialchars($editItem['horario'] ?? '') ?>">
          </div>
          <div class="mb-2">
            <label class="form-label">Tipo</label>
            <?php $tipo = $editItem['tipo'] ?? ''; ?>
            <select class="form-select" name="tipo" required>
              <option value="">Selecione...</option>
              <option value="Doa" <?= $tipo==='Doa'?'selected':'' ?>>Doa</option>
              <option value="Recebe" <?= $tipo==='Recebe'?'selected':'' ?>>Recebe</option>
              <option value="Doa/Recebe" <?= $tipo==='Doa/Recebe'?'selected':'' ?>>Doa/Recebe</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Alimentos aceitos</label>
            <textarea class="form-control" name="alimentos" rows="2" required><?= htmlspecialchars($editItem['alimentos'] ?? '') ?></textarea>
          </div>
          <div class="d-grid gap-2">
            <button class="btn btn-success" type="submit"><?= $editItem?'Salvar alterações':'Salvar' ?></button>
            <?php if ($editItem): ?><a class="btn btn-secondary" href="painel.php?tab=itens">Cancelar edição</a><?php endif; ?>
          </div>
        </form>
      </div></div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card rounded-xl shadow-soft"><div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 gap-2">
          <h5 class="mb-0">Pontos cadastrados</h5>
          <form class="d-flex gap-2" method="get" action="painel.php">
            <input type="hidden" name="tab" value="itens">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Buscar..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button class="btn btn-outline-secondary btn-sm">Filtrar</button>
          </form>
        </div>
        <?php
          $q2 = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM items WHERE 1=1";
$params=[]; $types="";
if (!is_admin()) { $sql .= " AND user_id = ?"; $params[] = $_SESSION['user']['id']; $types .= 'i'; }
if ($q2 !== '') {
  $sql .= " AND (nome LIKE ? OR endereco LIKE ? OR alimentos LIKE ?)";
  $like = '%'.$q2.'%';
  $params[] = $like; $params[] = $like; $params[] = $like; $types .= 'sss';
}
$sql .= " ORDER BY id DESC";
$st = mysqli_prepare($conn, $sql);
if (!is_admin() && $q2 !== '') {
  $like = '%'.$q2.'%'; mysqli_stmt_bind_param($st, 'isss', $_SESSION['user']['id'], $like, $like, $like);
} elseif (!is_admin() && $q2 === '') {
  mysqli_stmt_bind_param($st, 'i', $_SESSION['user']['id']);
} elseif (is_admin() && $q2 !== '') {
  $like = '%'.$q2.'%'; mysqli_stmt_bind_param($st, 'sss', $like, $like, $like);
}
mysqli_stmt_execute($st); $res = mysqli_stmt_get_result($st);
        ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead><tr>
              <th>Nome</th><th class="d-none d-lg-table-cell">Endereço</th>
              <th class="d-none d-xl-table-cell">Horário</th><th>Tipo</th>
              <th class="d-none d-xl-table-cell">Alimentos</th><th class="text-end">Ações</th>
            </tr></thead>
            <?php $uid = $_SESSION['user']['id'] ?? 0; ?>
<tbody>
<?php if (!$res || mysqli_num_rows($res)===0): ?>
              <tr><td colspan="6"><div class="alert alert-light border mb-0">Nenhum ponto cadastrado.</div></td></tr>
            <?php else: while($i=mysqli_fetch_assoc($res)): ?>
<?php if (!is_admin() && (int)($i['user_id'] ?? 0) !== (int)$uid) { continue; } ?>
              <?php $cls = $i['tipo']==='Doa'?'text-bg-success':($i['tipo']==='Recebe'?'text-bg-primary':'text-bg-warning'); ?>
              <tr>
                <td><?= htmlspecialchars($i['nome']) ?></td>
                <td class="d-none d-lg-table-cell"><?= htmlspecialchars($i['endereco']) ?></td>
                <td class="d-none d-xl-table-cell small text-muted"><?= htmlspecialchars($i['horario']) ?></td>
                <td><span class="badge <?= $cls ?>"><?= htmlspecialchars($i['tipo']) ?></span></td>
                <td class="d-none d-xl-table-cell small"><?= htmlspecialchars($i['alimentos']) ?></td>
                <td class="text-end">
  <div class="btn-group" role="group" aria-label="Ações">
    <a class="btn btn-outline-secondary" title="Editar" href="painel.php?tab=itens&edit=<?= (int)$i['id'] ?>">
      <i class="bi bi-pencil-square"></i>
    </a>
    <form action="../actions/item_delete.php" method="post" style="display:inline" onsubmit="return confirm('Excluir este ponto?')">
      <input type="hidden" name="id" value="<?= (int)$i['id'] ?>">
      <button type="submit" class="btn btn-outline-danger" title="Excluir">
        <i class="bi bi-trash"></i>
      </button>
    </form>
  </div>
</td>
              </tr>
            <?php endwhile; endif; ?>
            </tbody>
          </table>
        </div>
      </div></div>
    </div>
  </div>

<?php elseif ($tab==='perfil'): ?>
  <?php $u = $_SESSION['user']; ?>
  <div class="row">
    <div class="col-12 col-lg-6">
      <div class="card rounded-xl shadow-soft"><div class="card-body">
        <h5 class="card-title mb-3">Meu perfil</h5>
        <form action="../actions/me_update.php" method="post" class="needs-validation" novalidate>
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input class="form-control" name="nome" required minlength="3" value="<?= htmlspecialchars($u['nome']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input class="form-control" type="email" name="email" required value="<?= htmlspecialchars($u['email']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Senha (opcional)</label>
            <input class="form-control" type="password" name="senha" minlength="4" placeholder="Deixe em branco para manter">
          </div>
          <div class="d-grid gap-2">
            <button class="btn btn-success" type="submit">Salvar alterações</button>
          </div>
        </form>
        <hr>
        <form action="../actions/me_delete.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir sua conta?')">
          <button class="btn btn-outline-danger" type="submit">Excluir conta</button>
        </form>
      </div></div>
    </div>
  </div>

<?php else: ?>
  <?php if (!is_admin()): ?>
    <div class="alert alert-warning mb-0">Apenas administradores podem acessar esta seção.</div>
  <?php else: ?>
    <?php
      $res = mysqli_query($conn, "SELECT id,nome,email,role FROM users ORDER BY nome");
    ?>
    <div class="card rounded-xl shadow-soft"><div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Usuários</h5>
        <span class="badge text-bg-warning">Apenas administrador</span>
      </div>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead><tr><th>Nome</th><th>E-mail</th><th>Nível</th><th class="text-end">Ações</th></tr></thead>
          <?php $uid = $_SESSION['user']['id'] ?? 0; ?>
<tbody>
<?php if (!$res || mysqli_num_rows($res)===0): ?>
            <tr><td colspan="4"><div class="alert alert-light border mb-0">Nenhum usuário encontrado.</div></td></tr>
          <?php else: while($u=mysqli_fetch_assoc($res)): ?>
            <tr>
              <td><?= htmlspecialchars($u['nome']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['role']) ?></td>
              <td class="text-end">
                <?php if ((int)$u['id'] !== (int)$_SESSION['user']['id']): ?>
                <form action="../actions/user_delete.php" method="post" style="display:inline" onsubmit="return confirm('Excluir este usuário?')">
                  <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                  <button class="btn btn-outline-danger btn-sm"><i class="bi bi-person-x"></i></button>
                </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>
    </div></div>
  <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__.'/_footer.php'; ?>
