<?php
require 'db_config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $nickname = trim($_POST['nickname']);
    $color = $_POST['color'];

    if ($username === '' || $password === '' || $nickname === '') {
        $msg = '請填寫所有欄位';
    } else {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 頭像處理
        $avatarName = 'default.png';

        if (!empty($_FILES['avatar']['name'])) {

            $file = $_FILES['avatar'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($file['type'], $allowed)) {

                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $avatarName = uniqid() . '.' . $ext;

                move_uploaded_file($file['tmp_name'], 'avatar/' . $avatarName);

            } else {
                $msg = '只允許圖片格式';
            }
        }

        if ($msg === '') {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO users (username, password, nickname, color, avatar)
                     VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->execute([
                    $username,
                    $passwordHash,
                    $nickname,
                    $color,
                    $avatarName
                ]);

                $msg = '註冊成功，請登入';

            } catch (PDOException $e) {
                $msg = '帳號已存在';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>註冊</title>
</head>

<body>

<h2>會員註冊</h2>

<?php if ($msg): ?>
<p style="color:red;"><?= escape($msg) ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<div>
帳號：<br>
<input name="username">
</div>

<div>
密碼：<br>
<input type="password" name="password">
</div>

<div>
暱稱：<br>
<input name="nickname">
</div>

<div>
喜歡顏色：<br>
<input type="color" name="color" value="#ffffff">
</div>

<div>
頭像上傳：<br>
<input type="file" name="avatar">
</div>

<br>
<button type="submit">註冊</button>

</form>

<p>
<a href="login.php">已有帳號？登入</a>
</p>

</body>
</html>