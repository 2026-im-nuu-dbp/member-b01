<?php
session_start();
require 'db_config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = '請填寫帳號與密碼';
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $error = '帳號或密碼錯誤';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>登入</title>
</head>

<body>

<h2>會員登入</h2>

<?php if ($error): ?>
<p style="color:red;"><?= escape($error) ?></p>
<?php endif; ?>

<form method="post">

<div>
帳號：<br>
<input name="username">
</div>

<div>
密碼：<br>
<input type="password" name="password">
</div>

<br>
<button type="submit">登入</button>

</form>

<p>
<a href="register.php">還沒有帳號？註冊</a>
</p>

</body>
</html>