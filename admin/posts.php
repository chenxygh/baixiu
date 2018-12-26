<?php include 'inc/login_state.php'; ?>

<?php

require_once '../functions.php';

/* ===================== 数据格式转换 =============== */

/**
 * 转换状态显示
 * @param  string $status 英文
 * @return string        中文
 */
function convert_status ($status) {
	$dict = array('drafted' => '草稿', 'published' => '已发布', 'trashed' => '回收站');
	return empty($dict[$status])? '未知': $dict[$status];
}

/**
 * 转换时间格式
 * @param  [type] $created [description]
 * @return [type]          [description]
 */
function convert_time ($created) {
	return date('Y年m月d日<b\r/>H:i:s', strtotime($created));
}

// /**
//  * 通过 user_id 获取 nickname
//  * @param  [type] $user_id [description]
//  * @return [type]          [description]
//  */
// function convert_nickname ($user_id) {
// 	$nickname = xiu_select_one("select nickname from users where id={$user_id} limit 1;");
// 	return $nickname? $nickname['nickname']: '未知';
// }

// /**
//  * 通过 category_id 获取分类名称
//  * @param  [type] $category_id [description]
//  * @return [type]              [description]
//  */
// function convert_category ($category_id) {
// 	$name = xiu_select_one("select name from categories where id={$category_id} limit 1;");
// 	return $name? $name['name']: '未知';
// }

$select = 'select
	posts.id,
	posts.title,
	users.nickname as user_name,
	categories.`name`as category_name,
	posts.created,
	posts.`status`
from posts
inner join categories on posts.category_id = categories.id
inner join users on posts.user_id = users.id;';

$posts = xiu_select_all($select);

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Posts &laquo; Admin</title>
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
			<div class="page-title">
				<h1>所有文章</h1>
				<a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
			</div>
			<!-- 有错误信息时展示 -->
	  <!-- <div class="alert alert-danger">
		<strong>错误！</strong>发生XXX错误
	</div> -->
	<div class="page-action">
		<!-- show when multiple checked -->
		<a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
		<form class="form-inline">
			<select name="" class="form-control input-sm">
				<option value="">所有分类</option>
				<option value="">未分类</option>
			</select>
			<select name="" class="form-control input-sm">
				<option value="">所有状态</option>
				<option value="">草稿</option>
				<option value="">已发布</option>
			</select>
			<button class="btn btn-default btn-sm">筛选</button>
		</form>
		<ul class="pagination pagination-sm pull-right">
			<li><a href="#">上一页</a></li>
			<li><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">下一页</a></li>
		</ul>
	</div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center" width="40"><input type="checkbox"></th>
				<th>标题</th>
				<th>作者</th>
				<th>分类</th>
				<th class="text-center">发表时间</th>
				<th class="text-center">状态</th>
				<th class="text-center" width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($posts)): ?>
				<?php foreach ($posts as $item): ?>
					<tr>
						<td class="text-center"><input type="checkbox"></td>
						<!-- 随便一个名称	 小小 潮科技 2016/10/07 已发布 -->
						<td><?php echo $item['title']; ?></td>
						<td><?php echo $item['user_name']; ?></td>
						<td><?php echo $item['category_name']; ?></td>
						<td class="text-center"><?php echo convert_time($item['created']); ?></td>
						
						<td class="text-center"><?php echo convert_status($item['status']); ?></td>
						<td class="text-center">
							<a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
							<a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
						</td>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
		</tbody>
	</table>
</div>
</div>

<?php include 'inc/sidebar.php'; ?>

<script src="/static/assets/vendors/jquery/jquery.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
<script>NProgress.done()</script>
</body>
</html>
