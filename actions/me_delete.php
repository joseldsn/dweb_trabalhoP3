<?php require_once __DIR__.'/../includes/session.php'; require_login(); require_once __DIR__.'/../includes/db.php';
$id = (int)$_SESSION['user']['id'];
$st = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
mysqli_stmt_bind_param($st, "i", $id);
mysqli_stmt_execute($st);
$_SESSION = []; session_destroy();
header('Location: ../public/index.php');