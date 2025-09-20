<?php require_once __DIR__.'/../includes/session.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>NUTRI+</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-success" href="index.php">
      <i class="bi bi-heart-fill me-2"></i>NUTRI+
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="mainNav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <?php if (current_user()): ?>
          <li class="nav-item me-3"><span class="small text-muted-2">
            <?= htmlspecialchars($_SESSION['user']['nome']) ?> - <?= $_SESSION['user']['role']==='admin'?'Administrador':'Usuário' ?>
          </span></li>
                    <li class="nav-item me-2">
            <a class="btn btn-outline-primary btn-sm" href="painel.php">
              <i class="bi bi-speedometer2 me-1"></i> Painel
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-danger btn-sm" href="../actions/logout.php">
              <i class="bi bi-box-arrow-right me-1"></i> Sair
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-success" href="auth.php">
              <i class="bi bi-box-arrow-in-right me-1"></i> Entrar / Cadastrar
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">

