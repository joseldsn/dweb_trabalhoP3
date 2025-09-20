<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php'; require_login();
$nome = trim($_POST['nome'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
$me = $_SESSION['user'];

if (strlen($nome)<3 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['flash']='Campos inválidos.'; header('Location: ../public/painel.php?tab=perfil'); exit;
}

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email=? AND id<>? LIMIT 1");
mysqli_stmt_bind_param($stmt, "si", $email, $me['id']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if ($res && mysqli_fetch_assoc($res)) {
  $_SESSION['flash']='Já existe outro usuário com este e-mail.'; header('Location: ../public/painel.php?tab=perfil'); exit;
}

if ($senha) {
  $hash = password_hash($senha, PASSWORD_DEFAULT);
  $st = mysqli_prepare($conn, "UPDATE users SET nome=?, email=?, senha_hash=? WHERE id=?");
  mysqli_stmt_bind_param($st, "sssi", $nome,$email,$hash,$me['id']);
} else {
  $st = mysqli_prepare($conn, "UPDATE users SET nome=?, email=? WHERE id=?");
  mysqli_stmt_bind_param($st, "ssi", $nome,$email,$me['id']);
}
mysqli_stmt_execute($st);

$_SESSION['user']['nome'] = $nome;
$_SESSION['user']['email'] = $email;
$_SESSION['flash']='Perfil atualizado!'; header('Location: ../public/painel.php?tab=perfil');