<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php';
$nome = trim($_POST['nome'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';

if (strlen($nome)<3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha)<4) {
  $_SESSION['flash'] = 'Campos inválidos.';
  header('Location: ../public/auth.php?tab=cadastro'); exit;
}

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$exists = mysqli_stmt_get_result($stmt);
if ($exists && mysqli_fetch_assoc($exists)) {
  $_SESSION['flash'] = 'Já existe uma conta com este e-mail.';
  $_SESSION['err_email_exists'] = true;
  $_SESSION['old_nome'] = $nome;
  $_SESSION['old_email'] = $email;
  header('Location: ../public/auth.php?tab=cadastro'); exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt2 = mysqli_prepare($conn, "INSERT INTO users (nome,email,senha_hash,role) VALUES (?,?,?,'user')");
mysqli_stmt_bind_param($stmt2, "sss", $nome, $email, $hash);
mysqli_stmt_execute($stmt2);

$_SESSION['flash'] = 'Conta criada! Faça login.';
header('Location: ../public/auth.php?tab=login');