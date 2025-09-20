<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php'; require_login();
$id = (int)($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$horario = trim($_POST['horario'] ?? '');
$tipo = $_POST['tipo'] ?? '';
$alimentos = trim($_POST['alimentos'] ?? '');

if ($id<=0 || strlen($nome)<2 || strlen($endereco)<3 || strlen($horario)<3 || !in_array($tipo,['Doa','Recebe','Doa/Recebe'], true) || $alimentos==='') {
  $_SESSION['flash']='Preencha os campos corretamente.'; header('Location: ../public/painel.php?tab=itens&edit='.$id); exit;
}
$stmt = mysqli_prepare($conn, "UPDATE items SET nome=?,endereco=?,horario=?,tipo=?,alimentos=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "sssssi", $nome,$endereco,$horario,$tipo,$alimentos,$id);
mysqli_stmt_execute($stmt);
$_SESSION['flash']='Ponto atualizado!'; header('Location: ../public/painel.php?tab=itens');