<?php

require_once '../config.php';

session_start();

function login () {
	// 获取到邮箱和密码
	if (empty($_POST['email'])) {
		$GLOBALS['message'] = '请输入邮箱';
		return;
	}
	$email = $_POST['email'];
	if (empty($_POST['password'])) {
		$GLOBALS['message'] = '请输入密码';
		return;
	}
	$pass = $_POST['password'];

	// 连接数据库，校验邮箱和密码
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if (!$conn) {
		exit('连接数据库失败');
	}

	$select = "select * from users where email = '{$email}' limit 1;";
	$query = mysqli_query($conn, $select);
	if (!$query) {
		$GLOBALS['message'] = '查询失败';
		return;
	}
	$userinfo = mysqli_fetch_assoc($query);

	if (!$userinfo) {
		// 找不到邮箱
		$GLOBALS['message'] = '没有找到该用户';
		return;
	}
	if ($userinfo['password'] !== md5($pass)) {
		// 密码错误
		$GLOBALS['message'] = '邮箱和密码不匹配';
		return;
	}

	// 一切 OK, 设置 session, 跳转回 index.php
	$_SESSION['current_user'] = $userinfo;
	header('Location: /admin/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	login();
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Sign in &laquo; Admin</title>
	<link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
	<div class="login">
		<form class="login-wrap" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" novalidate>
			<img class="avatar" src="/static/assets/img/default.png">
			<!-- 有错误信息时展示 -->
			<?php if (!empty($message)): ?>
				<div class="alert alert-danger">
					<strong>错误！</strong> <?php echo $message; ?>
				</div>
			<?php endif ?>
			<div class="form-group">
				<label for="email" class="sr-only">邮箱</label>
				<input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo empty($_POST['email'])? '': $_POST['email']; ?>">
			</div>
			<div class="form-group">
				<label for="password" class="sr-only">密码</label>
				<input id="password" name="password" type="password" class="form-control" placeholder="密码">
			</div>
			<button class="btn btn-primary btn-block" href="index.html">登 录</button>
		</form>
	</div>
</body>
</html>
