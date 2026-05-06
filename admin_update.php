<?php
session_start();
require 'db_config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die('無權限');
}

$id = $_POST['id'];
$nickname = $_POST['nickname'];

$stmt = $pdo->prepare("UPDATE users SET nickname=? WHERE id=?");
$stmt->execute([$nickname, $id]);

header("Location: admin.php");