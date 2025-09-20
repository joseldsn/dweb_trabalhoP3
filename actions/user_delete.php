<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php'; require_login();
if (!is_admin()) { http_response_code(403); echo "Acesso negado."; exit; }
$id = (int)($_POST['id'] ?? 0);
if ($id<=0 || $id === (int)$_SESSION['user']['id']) { $_SESSION['flash']='Operação inválida.'; header('Location: ../public/painel.php?tab=usuarios'); exit; }
$st = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
mysqli_stmt_bind_param($st, "i", $id);
mysqli_stmt_execute($st);
$_SESSION['flash']='Usuário excluído.'; header('Location: ../public/painel.php?tab=usuarios');