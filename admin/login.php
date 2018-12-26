<?php

require_once '../config.php';

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
	$conn = mysqli_connect(XIU_DB_HOST, XIU_DB_USER, XIU_DB_PASS, XIU_DB_NAME);
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
	session_start();
	$_SESSION['current_user'] = $userinfo;
	session_write_close();

	header('Location: /admin/index.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	login();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['action']) && $_GET['action'] === 'logout') {
	session_start();
	unset($_SESSION['current_user']);
	session_write_close();
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Sign in &laquo; Admin</title>
	<link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/static/assets/css/admin.css">
	<link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
	<script src="/static/assets/vendors/jquery/jquery.js"></script>
	<script>
		$(function ($) {
			var oldEmail = [];
			$('#email').bind('blur', function () {
				// 失去焦点时，获取 email 文本框的内容
				var email = $('#email').val();

				// 正则校验邮箱
				var emailReg = /^[0-9a-zA-Z_.-]+@[0-9a-zA-Z_.-]+(\.[a-zA-Z]+){1,2}$/;
				if (!emailReg.test(email)) {
					// 邮箱有误，设置默认头像
					$('.avatar').attr({src: '/static/assets/img/default.png'});
					return;
				}

				// 如果和上次的输入一样，就不发送 ajax 更换头像
				// 注意，在第一次的时候，还没有历史纪录的时候，还是会发送一下 ajax 请求，这是完全合理的
				oldEmail.unshift(email);
				if (email === oldEmail.splice(1, 1)[0]) return;

				/**
				 *
				 * 提前的正则 看心情 不加了
				 * 
				 */

				// 发送 ajax 请求，获取用户信息，设置头像
				$.get('/admin/api/avatar.php', {email: email}, function (res) {
					// 短路计算
					var avatar = res['avatar'] || '/static/assets/img/default.png';
					$('.avatar').fadeOut(function () {
						// 淡出后
						$(this).bind('load', function () {
							// 图片加载完成后 淡入
							$(this).fadeIn();
						}).attr({src: avatar});
					});
				});
			});
		});
	</script>
</head>
<body>
	<div class="login<?php echo empty($message)? '': ' shake animated' ?>">
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
