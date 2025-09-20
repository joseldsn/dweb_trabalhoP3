<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php'; require_login();
$id = (int)($_POST['id'] ?? 0);
if ($id<=0) { $_SESSION['flash']='ID inválido.'; header('Location: ../public/painel.php?tab=itens'); exit; }

$chk = mysqli_prepare($conn, "SELECT user_id FROM items WHERE id=? LIMIT 1");
mysqli_stmt_bind_param($chk, "i", $id);
mysqli_stmt_execute($chk);
$ownres = mysqli_stmt_get_result($chk);
$own = $ownres ? mysqli_fetch_assoc($ownres) : null;
if (!$own) { $_SESSION['flash']='Registro não encontrado.'; header('Location: ../public/painel.php?tab=itens'); exit; }
if (!is_admin() && (int)$own['user_id'] !== (int)$_SESSION['user']['id']) {
  http_response_code(403);
  $_SESSION['flash']='Você não tem permissão para excluir este ponto.';
  header('Location: ../public/painel.php?tab=itens'); exit;
}
$stmt = mysqli_prepare($conn, "DELETE FROM items WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$_SESSION['flash']='Ponto excluído.'; header('Location: ../public/painel.php?tab=itens');