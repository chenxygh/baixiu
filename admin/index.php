<?php include 'inc/login_state.php'; ?>
<?php

// 站点内容统计
require_once '../functions.php';

// 查询语句
$posts_cnt = xiu_select_one('select count(1) as cnt from posts;');
$posts_cnt = empty($posts_cnt)? -1: $posts_cnt['cnt'];

$drafted_cnt = xiu_select_one("select count(1) as cnt from posts where status = 'drafted';");
$drafted_cnt = empty($drafted_cnt)? -1: $drafted_cnt['cnt'];

$categories_cnt = xiu_select_one('select count(1) as cnt from categories;');
$categories_cnt = empty($categories_cnt)? -1: $categories_cnt['cnt'];

$comments_cnt = xiu_select_one('select count(1) as cnt from comments;');
$comments_cnt = empty($comments_cnt)? -1: $comments_cnt['cnt'];

$held_cnt = xiu_select_one("select count(1) as cnt from comments where status = 'held';");
$held_cnt = empty($held_cnt)? -1: $held_cnt['cnt'];


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Dashboard &laquo; Admin</title>
	<link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
	<link rel="stylesheet" href="/static/assets/css/admin.css">
	<script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
	<script>NProgress.start()</script>

	<div class="main">
		<?php include 'inc/navbar.php'; ?>
		<div class="container-fluid">
			<div class="jumbotron text-center">
				<h1>One Belt, One Road</h1>
				<p>Thoughts, stories and ideas.</p>
				<p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">站点内容统计：</h3>
						</div>
						<ul class="list-group">
							<li class="list-group-item"><strong><?php echo $posts_cnt; ?></strong>篇文章（<strong><?php echo $drafted_cnt; ?></strong>篇草稿）</li>
							<li class="list-group-item"><strong><?php echo $categories_cnt; ?></strong>个分类</li>
							<li class="list-group-item"><strong><?php echo $comments_cnt; ?></strong>条评论（<strong><?php echo $held_cnt; ?></strong>条待审核）</li>
						</ul>
					</div>
				</div>
				<div class="col-md-4">
					<canvas id="chart-area"></canvas>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
	</div>

	<?php include 'inc/sidebar.php'; ?>

	<script src="/static/assets/vendors/jquery/jquery.js"></script>
	<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
	<script src="/static/assets/vendors/chart/chart.js"></script>
	<script>NProgress.done()</script>
	<script>
		$(function () {
			var config = {
				type: 'pie',
				data: {
					datasets: [{
						data: [
						<?php echo $posts_cnt ?>,
						<?php echo $drafted_cnt ?>,
						<?php echo $categories_cnt ?>,
						<?php echo $comments_cnt ?>,
						<?php echo $held_cnt ?>
						],
						backgroundColor: [
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)'
						],
						label: 'heartbeat 1'
					}],
					labels: [
					'posts_cnt',
					'drafted_cnt',
					'categories_cnt',
					'comments_cnt',
					'held_cnt'
					]
				},
				options: {
					responsive: true
				}
			};

			window.onload = function() {
				var ctx = document.getElementById('chart-area').getContext('2d');
				window.myPie = new Chart(ctx, config);
			};
		});
	</script>
</body>
</html>
