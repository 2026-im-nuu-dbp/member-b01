<?php
session_start();
require 'db_config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die('無權限');
}

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
$stmt->execute([$id]);

header("Location: admin.php");