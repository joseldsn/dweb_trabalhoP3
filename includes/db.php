<?php
$HOST = 'localhost';
$USER = 'root';
$PASS = '';
$DB   = 'bd_nutri_plus';

$conn = mysqli_connect($HOST, $USER, $PASS, $DB);
if (!$conn) {
  die('Erro na conexão: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
