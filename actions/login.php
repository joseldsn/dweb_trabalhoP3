<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php';
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$u = $res ? mysqli_fetch_assoc($res) : null;

if (!$u || !password_verify($senha, $u['senha_hash'])) {
  $_SESSION['flash'] = 'Credenciais inválidas.';
  $_SESSION['err_login'] = true;
  $_SESSION['old_email'] = $email;
  header('Location: ../public/auth.php?tab=login'); exit;
}

$_SESSION['user'] = ['id'=>$u['id'],'nome'=>$u['nome'],'email'=>$u['email'],'role'=>$u['role']];
header('Location: ../public/painel.php');