<?php require_once __DIR__.'/../includes/session.php';
$_SESSION = []; session_destroy();
header('Location: ../public/index.php');