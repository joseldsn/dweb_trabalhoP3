<?php require_once __DIR__.'/_header.php'; ?>
<?php
$err_login = $_SESSION['err_login'] ?? false;
$err_email_exists = $_SESSION['err_email_exists'] ?? false;
$old_email = $_SESSION['old_email'] ?? '';
$old_nome = $_SESSION['old_nome'] ?? '';
unset($_SESSION['err_login'], $_SESSION['err_email_exists'], $_SESSION['old_email'], $_SESSION['old_nome']);
?>
<?php $tab = $_GET['tab'] ?? 'login'; ?>

<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item"><a class="nav-link <?= $tab==='login'?'active':'' ?>" href="auth.php?tab=login">Login</a></li>
  <li class="nav-item"><a class="nav-link <?= $tab==='cadastro'?'active':'' ?>" href="auth.php?tab=cadastro">Cadastro</a></li>
</ul>

<div class="border-start border-end border-bottom p-4 bg-white rounded-bottom shadow-soft">
<?php if ($tab==='login'): ?>
  <form action="../actions/login.php" method="post" novalidate class="needs-validation">
    <div class="mb-3">
      <label for="loginEmail" class="form-label">E-mail</label>
      <input type="email" id="loginEmail" name="email" class="form-control <?= $err_login?'is-invalid':'' ?>" value="<?= htmlspecialchars($old_email) ?>" required>
      <div class="invalid-feedback">Informe um e-mail válido.</div>
<?php if ($err_email_exists): ?><div class="invalid-feedback d-block">Já existe uma conta com este e-mail.</div><?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="loginSenha" class="form-label">Senha</label>
      <input type="password" id="loginSenha" name="senha" class="form-control <?= $err_login?'is-invalid':'' ?>" required minlength="4">
      <div class="invalid-feedback">Informe sua senha.</div>
<?php if ($err_login): ?><div class="invalid-feedback d-block">Credenciais inválidas.</div><?php endif; ?>
    </div>
    <div class="d-grid gap-2">
      <button class="btn btn-success" type="submit">Entrar</button>
      <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar para a página inicial</a>
    </div>
  </form>
<?php else: ?>
  <form action="../actions/register.php" method="post" novalidate class="needs-validation">
    <div class="mb-3">
      <label for="cadNome" class="form-label">Nome completo</label>
      <input type="text" id="cadNome" name="nome" class="form-control" required minlength="3" value="<?= htmlspecialchars($old_nome) ?>">
      <div class="invalid-feedback">Informe seu nome.</div>
    </div>
    <div class="mb-3">
      <label for="cadEmail" class="form-label">E-mail</label>
      <input type="email" id="cadEmail" name="email" class="form-control <?= $err_email_exists?'is-invalid':'' ?>" required value="<?= htmlspecialchars($old_email) ?>">
      <div class="invalid-feedback">Informe um e-mail válido.</div>
<?php if ($err_email_exists): ?><div class="invalid-feedback d-block">Já existe uma conta com este e-mail.</div><?php endif; ?>
    </div>
    <div class="mb-3">
      <label for="cadSenha" class="form-label">Senha</label>
      <input type="password" id="cadSenha" name="senha" class="form-control" required minlength="4">
      <div class="invalid-feedback">Crie uma senha (mínimo 4 caracteres).</div>
    </div>
    <div class="d-grid">
      <button class="btn btn-success" type="submit">Criar conta</button>
    </div>
  </form>
<?php endif; ?>
</div>

<?php require_once __DIR__.'/_footer.php'; ?>
