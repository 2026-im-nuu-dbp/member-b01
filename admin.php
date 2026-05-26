<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('無權限');
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<h2>會員管理</h2>
<div style="margin-bottom:15px;">
    <a href="index.php">回到討論區</a>
</div>
<?php foreach ($users as $u): ?>

<form method="post" action="admin_update.php">
    <input type="hidden" name="id" value="<?= $u['id'] ?>">

    帳號：<?= escape($u['username']) ?><br>

    暱稱：
    <input name="nickname" value="<?= escape($u['nickname']) ?>">

    <button>修改</button>
</form>

<form method="post" action="admin_delete.php">
    <input type="hidden" name="id" value="<?= $u['id'] ?>">
    <button onclick="return confirm('確定刪除？')">刪除</button>
</form>

<hr>

<?php endforeach; ?>