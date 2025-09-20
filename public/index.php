<?php require_once __DIR__.'/_header.php'; require_once __DIR__.'/../includes/db.php'; ?>
<header class="hero py-5">
  <div class="container">
    <h1 class="fw-semibold mb-3"><i class="bi bi-heart-fill text-success me-2"></i>
      NUTRI+ - Plataforma de Doação e Redistribuição de Alimentos</h1>
    <p class="lead mb-2">
      Conecte-se a locais que <strong>doam</strong> ou <strong>recebem</strong> alimentos. Endereço,
      horários e itens aceitos com transparência.
    </p>
    <p class="lead-quiet mb-0">Alinhado ao <strong>ODS 2 - Fome Zero</strong>.</p>
  </div>
</header>

<?php
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

$sql = "SELECT * FROM items WHERE 1=1";
$params = [];
$types = "";

if ($q !== "") {
  $sql .= " AND (nome LIKE ? OR endereco LIKE ? OR alimentos LIKE ?)";
  $like = "%".$q."%"; $params[] = $like; $params[] = $like; $params[] = $like; $types .= "sss";
}
if ($tipo !== "") {
  $sql .= " AND tipo = ?"; $params[] = $tipo; $types .= "s";
}
$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
if ($q !== '' && $tipo !== '') {
  $like = '%'.$q.'%';
  mysqli_stmt_bind_param($stmt, 'ssss', $like, $like, $like, $tipo);
} elseif ($q !== '') {
  $like = '%'.$q.'%';
  mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
} elseif ($tipo !== '') {
  mysqli_stmt_bind_param($stmt, 's', $tipo);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
?>

<form class="row g-3 align-items-end mt-3" method="get" action="index.php">
  <div class="col-12 col-md-6">
    <label for="buscaPublica" class="form-label">Buscar ponto (nome, endereço, alimento)</label>
    <input type="text" id="buscaPublica" name="q" class="form-control" value="<?= htmlspecialchars($q) ?>">
  </div>
  <div class="col-6 col-md-3">
    <label for="filtroTipoPublico" class="form-label">Tipo</label>
    <select id="filtroTipoPublico" name="tipo" class="form-select">
      <option value="" <?= $tipo===''?'selected':'' ?>>Todos</option>
      <option value="Doa" <?= $tipo==='Doa'?'selected':'' ?>>Doa</option>
      <option value="Recebe" <?= $tipo==='Recebe'?'selected':'' ?>>Recebe</option>
      <option value="Doa/Recebe" <?= $tipo==='Doa/Recebe'?'selected':'' ?>>Doa/Recebe</option>
    </select>
  </div>
  <div class="col-6 col-md-3 d-grid">
    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Filtrar</button>
  </div>
</form>

<hr class="my-4">
<div class="row g-3">
<?php if (!count($rows)): ?>
  <div class="col-12"><div class="alert alert-light border">Nenhum ponto encontrado.</div></div>
<?php else: foreach ($rows as $i): ?>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <h5 class="card-title mb-1"><?= htmlspecialchars($i['nome']) ?></h5>
          <?php $cls = $i['tipo']==='Doa'?'text-bg-success':($i['tipo']==='Recebe'?'text-bg-primary':'text-bg-warning'); ?>
          <span class="badge <?= $cls ?>"><?= htmlspecialchars($i['tipo']) ?></span>
        </div>
        <p class="mb-1 small"><strong>Endereço:</strong> <?= htmlspecialchars($i['endereco']) ?></p>
        <p class="mb-1 small"><strong>Horário:</strong> <?= htmlspecialchars($i['horario']) ?></p>
        <p class="mb-0 small text-muted"><strong>Alimentos:</strong> <?= htmlspecialchars($i['alimentos']) ?></p>
      </div>
    </div>
  </div>
<?php endforeach; endif; ?>
</div>
<?php require_once __DIR__.'/_footer.php'; ?>
