<?php require_once __DIR__.'/../includes/session.php'; require_once __DIR__.'/../includes/db.php'; require_login();
$nome = trim($_POST['nome'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$horario = trim($_POST['horario'] ?? '');
$tipo = $_POST['tipo'] ?? '';
$alimentos = trim($_POST['alimentos'] ?? '');

if (strlen($nome)<2 || strlen($endereco)<3 || strlen($horario)<3 || !in_array($tipo,['Doa','Recebe','Doa/Recebe'], true) || $alimentos==='') {
  $_SESSION['flash']='Preencha os campos corretamente.'; header('Location: ../public/painel.php?tab=itens'); exit;
}
$stmt = mysqli_prepare($conn, "INSERT INTO items (nome,endereco,horario,tipo,alimentos,user_id) VALUES (?,?,?,?,?,?)");
mysqli_stmt_bind_param($stmt, "sssssi", $nome,$endereco,$horario,$tipo,$alimentos, $_SESSION['user']['id']);
mysqli_stmt_execute($stmt);
$_SESSION['flash']='Ponto salvo!'; header('Location: ../public/painel.php?tab=itens');