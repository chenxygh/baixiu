<?php include 'inc/login_state.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Navigation douban &laquo; Admin</title>
	<link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
	<link rel="stylesheet" href="/static/assets/css/admin.css">
	<script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
	<script>NProgress.start()</script>

	<div class="main">
		<nav class="navbar">
			<button class="btn btn-default navbar-btn fa fa-bars"></button>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="profile.php"><i class="fa fa-user"></i>个人中心</a></li>
				<li><a href="logout.php"><i class="fa fa-sign-out"></i>退出</a></li>
			</ul>
		</nav>
		<div class="container-fluid">
			<div class="page-title">
				<h1>导航菜单</h1>
			</div>
		</div>
		<ul id="theater"></ul>
	</div>	

	<?php include 'inc/sidebar.php'; ?>

	<script src="/static/assets/vendors/jquery/jquery.js"></script>
	<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
	<script>
		$(function () {
			$.ajax({
				type: 'GET',
				url: 'https://api.douban.com/v2/movie/in_theaters',
				dataType: 'jsonp',
				success: function (res) {
					var $theater = $('#theater');
					$(res.subjects).each(function (index, item) {
						$theater.append(`<li><img src="${item['images']['large']}" alt=""/>${item['title']}</li>`);
					});
				}
			})
		});
	</script>
	<script>NProgress.done()</script>
</body>
</html>
