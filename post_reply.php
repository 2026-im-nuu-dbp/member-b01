<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

$newsId = isset($_POST['news_id']) ? intval($_POST['news_id']) : 0;
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if ($newsId <= 0) {
    die('無效的討論 ID。<br><a href="index.php">返回</a>');
}

if ($content === '') {
    die('內容不可空白');
}

$user = $_SESSION['user'];
$author = $user['nickname'];
$user_id = $user['id'];

$content = substr($content, 0, 10000);

// 檢查文章是否存在
$stmt = $pdo->prepare("SELECT id FROM news WHERE id = ?");
$stmt->execute([$newsId]);

if (!$stmt->fetch()) {
    die('找不到此討論。<br><a href="index.php">返回首頁</a>');
}

// 寫入回覆（會員版）
$stmt = $pdo->prepare(
    "INSERT INTO replies (news_id, content, author, user_id)
     VALUES (?, ?, ?, ?)"
);

$stmt->execute([
    $newsId,
    $content,
    $author,
    $user_id
]);

header('Location: show_news.php?id=' . $newsId);
exit;