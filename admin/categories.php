<?php include 'inc/login_state.php'; ?>
<?php

require_once '../functions.php';

if (!empty($_GET['id'])) {
	$id = $_GET['id'];
	// 根据 id 获取要修改的信息
	$category_one = xiu_select_one("select * from categories where id = {$id} limit 1;");
}

function add () {
	if (empty($_POST['name']) || empty($_POST['slug'])) {
		$GLOBALS['success'] = false;
		$GLOBALS['message'] = '请完整填写信息';
		return;
	}
	$name = $_POST['name'];
	$slug = $_POST['slug'];

	// 向数据库里添加
	$affected_rows = xiu_execute("insert into categories value (null, '" . $slug . "', '" . $name . "');");
	$GLOBALS['success'] = $affected_rows > 0;
	$GLOBALS['message'] = $affected_rows <= 0? '添加失败': '添加成功';
}

function edit () {
	global $id;
	global $category_one;

	$name = empty($_POST['name'])? $category_one['name']: $_POST['name'];
	$category_one['name'] = $name;// 更新本地数据

	$slug = empty($_POST['slug'])? $category_one['slug']: $_POST['slug'];
	$category_one['slug'] = $slug;

	// 向数据库里更新
	$affected_rows = xiu_execute("update categories set name = '{$name}', slug = '{$slug}' where id = {$id};");
	$GLOBALS['success'] = $affected_rows > 0;
	$GLOBALS['message'] = $affected_rows <= 0? '更新失败': '更新成功';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (empty($_GET['id'])) {
		add();
	} else {
		edit();
	}
}

// select 总是在 增删改 之后，这样可以保持数据的最新
// 获取分类数据
$categories = xiu_select_all('select * from categories;');

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<title>Categories &laquo; Admin</title>
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
				<h1>分类目录</h1>
			</div>
			<!-- 有错误信息时展示 -->
			<?php if (!empty($message)): ?>
				<?php if ($success): ?>
					<div class="alert alert-success">
						<strong>成功！</strong><?php echo $message; ?>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">
						<strong>错误！</strong><?php echo $message; ?>
					</div>
				<?php endif ?>
			<?php endif ?>
			<div class="row">
				<div class="col-md-4">
					<?php if (empty($category_one)): ?>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
							<h2>添加新分类目录</h2>
							<div class="form-group">
								<label for="name">名称</label>
								<input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
							</div>
							<div class="form-group">
								<label for="slug">别名</label>
								<input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
								<p class="help-block">https://zce.me/category/<strong>slug</strong></p>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit">添加</button>
							</div>
						</form>
					<?php else: ?>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $category_one['id']; ?>" method="post">
							<h2>修改分类目录</h2>
							<div class="form-group">
								<label for="name">名称</label>
								<input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo $category_one['name']; ?>">
							</div>
							<div class="form-group">
								<label for="slug">别名</label>
								<input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $category_one['slug']; ?>">
								<p class="help-block">https://zce.me/category/<strong>slug</strong></p>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit">修改</button>
							</div>
						</form>
					<?php endif ?>
				</div>
				<div class="col-md-8">
					<div class="page-action">
						<!-- show when multiple checked -->
						<a class="btn btn-danger btn-sm" href="/admin/categories_del.php" style="display: none" id="del_all">批量删除</a>
					</div>
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center" width="40"><input type="checkbox" id="J_cbAll"></th>
								<th>名称</th>
								<th>Slug</th>
								<th class="text-center" width="100">操作</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($categories)): ?>
								<?php foreach ($categories as $item): ?>
									<tr>
										<td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
										<td><?php echo $item['name'] ?></td>
										<td><?php echo $item['slug'] ?></td>
										<td class="text-center">
											<a href="/admin/categories.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-xs">编辑</a>
											<a href="/admin/categories_del.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
										</td>
									</tr>
								<?php endforeach ?>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<?php include 'inc/sidebar.php'; ?>

	<script src="/static/assets/vendors/jquery/jquery.js"></script>
	<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
	<script>NProgress.done()</script>
	<script>
		$(function ($) {
			var cbAll = $('#J_cbAll');
			var cbs = $('tbody :checkbox');
			var delAll = $('#del_all');

			cbAll.bind('change', function () {
				cbs.prop({checked: cbAll.prop('checked')});
				cbs.triggerHandler('change');
			});

			cbs.bind('change', function () {
				var checkedBox = $('tbody :checked');

				cbAll.prop({checked: checkedBox.length === cbs.length});

				checkedBox.length? delAll.fadeIn(): delAll.fadeOut();

				var temp = [];
				checkedBox.each(function () {
					/**
					 * h5 中的新特性，标签属性中 加 data- 前缀的，会在对应 DOM 对象中有一个 dataset 属性来存放
					 * 比如，这里，原生 js 可以 this.dataset['id'], 获取的是 字符串
					 * jQuery $(this).data('id')， 获取的是有格式的，比如是 num
					 *
					 * console.log(this.dataset['id']);// str 类型
					 * console.log($(this).data('id'));// num 类型
					 */
					temp.push($(this).data('id'));
				});
				var data = temp.join(',');
				/**
				 * a 标签的 DOM 对象对应的有一个 search 属性，可以通过这个属性，设置 url 中的一些参数（网站路径后面）
				 */
				delAll.prop('search', '?id=' + data);
			});
		});
	</script>
</body>
</html>
