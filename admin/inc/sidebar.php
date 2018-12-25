<?php

// TODO: 根据当前访问页面，设置不同的侧边栏高亮
$path = $_SERVER['PHP_SELF'];
$filename = pathinfo($path, PATHINFO_FILENAME);

?>

<div class="aside">
	<div class="profile">
		<img class="avatar" src="/static/uploads/avatar.jpg">
		<h3 class="name">布头儿</h3>
	</div>
	<ul class="nav">
		<li<?php echo $filename === 'index'? ' class="active"': ''; ?>>
			<a href="index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
		</li>
		<?php $name_set = array('posts', 'post-add', 'categories'); ?>
		<?php $in = in_array($filename, $name_set)? 'in': ''; ?>
		<li<?php !empty($in)? ' class="active"': ''; ?>>
			<a href="#menu-posts" data-toggle="collapse"<?php echo !empty($in)? '': ' class="collapsed"'; ?>>
				<i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
			</a>
			<ul id="menu-posts" class="collapse <?php echo $in; ?>">
				<li<?php echo $filename === 'posts'? ' class="active"': ''; ?>><a href="posts.php">所有文章</a></li>
				<li<?php echo $filename === 'post-add'? ' class="active"': ''; ?>><a href="post-add.php">写文章</a></li>
				<li<?php echo $filename === 'categories'? ' class="active"': ''; ?>><a href="categories.php">分类目录</a></li>
			</ul>
		</li>
		<li<?php echo $filename === 'comments'? ' class="active"': ''; ?>>
			<a href="comments.php"><i class="fa fa-comments"></i>评论</a>
		</li>
		<li<?php echo $filename === 'users'? ' class="active"': ''; ?>>
			<a href="users.php"><i class="fa fa-users"></i>用户</a>
		</li>
		<?php $name_set = array('nav-menus', 'slides', 'settings'); ?>
		<?php $in = in_array($filename, $name_set)? 'in': ''; ?>
		<li<?php !empty($in)? ' class="active"': ''; ?>>
			<a href="#menu-settings"<?php echo !empty($in)? '': ' class="collapsed"'; ?> data-toggle="collapse">
				<i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
			</a>
			<ul id="menu-settings" class="collapse <?php echo $in; ?>">
				<li<?php echo $filename === 'nav-menus'? ' class="active"': ''; ?>><a href="nav-menus.php">导航菜单</a></li>
				<li<?php echo $filename === 'slides'? ' class="active"': ''; ?>><a href="slides.php">图片轮播</a></li>
				<li<?php echo $filename === 'settings'? ' class="active"': ''; ?>><a href="settings.php">网站设置</a></li>
			</ul>
		</li>
	</ul>
</div>
